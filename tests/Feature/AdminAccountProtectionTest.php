<?php

namespace Tests\Feature;

use App\Models\AdminPermission;
use App\Models\AdminRole;
use App\Models\AdminUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccountProtectionTest extends TestCase
{
    use RefreshDatabase;

    private function account(string $name, array $permissions = []): AdminUser
    {
        $role = AdminRole::firstOrCreate(['name' => $name], ['display_name' => ucfirst($name)]);
        foreach ($permissions as $permission) {
            $p = AdminPermission::firstOrCreate(['name' => $permission], ['display_name' => $permission, 'module' => 'users']);
            $role->permissions()->syncWithoutDetaching([$p->id]);
        }

        return AdminUser::create(['first_name' => 'Test', 'last_name' => 'Administrator', 'email' => $name.'@example.test',
            'username' => $name, 'password' => bcrypt('Password123!'), 'status' => 'active', 'role_id' => $role->id]);
    }

    public function test_user_manager_cannot_reset_a_super_admin_password(): void
    {
        $owner = $this->account('super_admin');
        $manager = $this->account('manager', ['reset_user_password']);
        $oldHash = $owner->password;
        $this->actingAs($manager, 'admin')->postJson(route('admin.users.reset-password', $owner), ['new_password' => 'Changed123!'])->assertForbidden();
        $this->assertSame($oldHash, $owner->fresh()->password);
    }

    public function test_user_manager_cannot_assign_the_super_admin_role(): void
    {
        $owner = $this->account('super_admin');
        $manager = $this->account('manager', ['create_users']);
        $this->actingAs($manager, 'admin')->postJson(route('admin.users.store'), [
            'first_name' => 'New', 'last_name' => 'Owner', 'email' => 'new@example.test', 'username' => 'new_owner',
            'password' => 'Password123!', 'role_id' => $owner->role_id, 'status' => 'active',
        ])->assertForbidden();
        $this->assertDatabaseMissing('admin_users', ['username' => 'new_owner']);
    }

    public function test_last_active_super_admin_cannot_be_disabled(): void
    {
        $owner = $this->account('super_admin');
        $this->actingAs($owner, 'admin')->putJson(route('admin.users.update', $owner), [
            'first_name' => $owner->first_name, 'last_name' => $owner->last_name, 'email' => $owner->email,
            'username' => $owner->username, 'role_id' => $owner->role_id, 'status' => 'inactive',
        ])->assertUnprocessable()->assertJsonValidationErrors('status');
        $this->assertSame('active', $owner->fresh()->status);
    }

    public function test_removing_a_member_from_the_wrong_role_does_not_change_their_real_role(): void
    {
        $owner = $this->account('super_admin');
        $member = $this->account('staff');
        $other = AdminRole::create(['name' => 'unrelated', 'display_name' => 'Unrelated']);
        $this->actingAs($owner, 'admin')->deleteJson(route('admin.roles.members.remove', [$other, $member]))->assertNotFound();
        $this->assertSame($member->role_id, $member->fresh()->role_id);
    }

    public function test_bulk_suspension_cannot_remove_the_current_owner(): void
    {
        $owner = $this->account('super_admin');
        $this->actingAs($owner, 'admin')->postJson(route('admin.users.bulk-action'), ['action' => 'suspend', 'user_ids' => [$owner->id]])
            ->assertUnprocessable();
        $this->assertSame('active', $owner->fresh()->status);
    }

    public function test_archived_admin_ids_are_not_reused(): void
    {
        $first = $this->account('staff');
        $first->delete();
        $next = $this->account('other');
        $this->assertNotSame($first->user_id, $next->user_id);
    }

    public function test_admin_changes_commit_with_readable_audit_records(): void
    {
        $owner = $this->account('super_admin');
        $staffRole = AdminRole::create(['name' => 'staff']);
        $this->actingAs($owner, 'admin')->post(route('admin.users.store'), [
            'first_name' => 'Maria', 'last_name' => 'Santos', 'email' => 'maria@example.test',
            'username' => 'maria', 'password' => 'Password123!', 'role_id' => $staffRole->id,
        ])->assertRedirect(route('admin.users.index'))->assertSessionHas('success')->assertSessionHasNoErrors();
        $staff = AdminUser::where('username', 'maria')->firstOrFail();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('Password123!', $staff->password));
        $this->assertSame('Staff', $staffRole->display_name);
        $audit = \App\Models\AdminActivityLog::where('action', 'CREATE')->firstOrFail();
        $this->assertSame($staff->id, $audit->details['user_id']);

        $this->put(route('admin.users.update', $staff), [
            'first_name' => 'Maria', 'last_name' => 'Santos', 'email' => $staff->email,
            'username' => $staff->username, 'role_id' => $staffRole->id, 'status' => 'inactive',
        ])->assertSessionHas('success')->assertSessionHasNoErrors();
        $this->assertSame('inactive', $staff->fresh()->status);
        $this->assertSame('inactive', \App\Models\AdminActivityLog::where('action', 'UPDATE')->firstOrFail()->details['changes']['status']);
        $this->post(route('admin.users.reset-password', $staff), ['new_password' => 'Updated123!'])
            ->assertSessionHas('success');
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('Updated123!', $staff->fresh()->password));
        $this->assertDatabaseHas('admin_activity_logs', ['action' => 'RESET_PASSWORD']);
    }

    public function test_last_owner_cannot_be_removed_or_moved_through_role_membership(): void
    {
        $owner = $this->account('super_admin');
        $staffRole = AdminRole::create(['name' => 'staff']);
        $this->actingAs($owner, 'admin')->deleteJson(route('admin.roles.members.remove', [$owner->role_id, $owner->id]))
            ->assertUnprocessable()->assertJsonValidationErrors('status');
        $this->postJson(route('admin.roles.members.add', $staffRole), ['user_id' => $owner->id])
            ->assertUnprocessable()->assertJsonValidationErrors('status');
        $this->assertSame($owner->role_id, $owner->fresh()->role_id);
    }

    public function test_bulk_delete_permission_does_not_grant_account_status_permission(): void
    {
        $manager = $this->account('manager', ['bulk_delete_users']);
        $staff = $this->account('staff');
        $this->actingAs($manager, 'admin')->postJson(route('admin.users.bulk-action'), ['action' => 'suspend', 'user_ids' => [$staff->id]])
            ->assertForbidden();
        $this->assertSame('active', $staff->fresh()->status);
    }

    public function test_invalid_account_forms_do_not_flash_passwords_into_the_session(): void
    {
        $owner = $this->account('super_admin');
        $this->actingAs($owner, 'admin')->post(route('admin.users.store'), ['password' => 'Sensitive123!', 'username' => 'incomplete'])
            ->assertSessionHasErrors('first_name')->assertSessionHasInput('username', 'incomplete');
        $this->assertNull(session()->getOldInput('password'));
        $this->post(route('admin.users.reset-password', $owner), ['new_password' => 'weak'])
            ->assertSessionHasErrors('new_password');
        $this->assertNull(session()->getOldInput('new_password'));
    }

    public function test_audit_details_support_existing_double_encoded_records_without_losing_dates(): void
    {
        $owner = $this->account('super_admin');
        $details = ['remarks' => 'Reviewed', 'created_at' => '2026-01-01', 'nested' => ['timestamp' => '123']];
        $log = \App\Models\AdminActivityLog::create(['user_id' => $owner->id, 'action' => 'TEST_AUDIT', 'module' => 'Users', 'details' => json_encode($details)]);
        $this->assertSame($details, $log->fresh()->details);
        \Illuminate\Support\Facades\DB::table('admin_activity_logs')->where('id', $log->id)->update(['details' => json_encode(json_encode($details))]);
        $this->assertSame($details, $log->fresh()->details);
    }

    public function test_residency_rejection_requires_a_reason_and_saves_valid_status_history(): void
    {
        $owner = $this->account('super_admin');
        $resident = \App\Models\Residents::factory()->create();
        $this->actingAs($resident)->post('/residency/store', [
            'profile_confirmed' => 'on', 'years_residing' => '5-10', 'residency_type' => 'owner',
            'household_members' => 3, 'purpose' => 'employment', 'terms' => 'on', 'privacy' => 'on', 'pickup' => 'on',
        ])->assertSessionHasNoErrors();
        $request = \App\Models\Residency::firstOrFail();
        $previousStatus = $request->status;
        $this->actingAs($owner, 'admin')->postJson(route('admin.residency.status', $request), ['status' => 'rejected'])
            ->assertUnprocessable()->assertJsonPath('errors.remarks.0', 'Enter a reason before rejecting this request.');
        $this->assertSame($previousStatus, $request->fresh()->status);
        $this->post(route('admin.residency.status', $request), ['status' => 'rejected', 'remarks' => 'Please correct address'])
            ->assertSessionHasNoErrors()->assertRedirect();
        $this->assertSame('rejected', $request->fresh()->status);
        $this->assertDatabaseHas('status_remark_histories', ['request_type' => 'residency', 'request_id' => $request->id, 'remarks' => 'Please correct address']);
    }

    public function test_reset_defaults_changes_only_the_selected_role(): void
    {
        $owner = $this->account('super_admin');
        $staff = $this->account('staff', ['view_residents']);
        $captain = $this->account('barangay_captain', ['create_users']);
        $original = $captain->role->permissions->pluck('id')->all();
        $this->actingAs($owner, 'admin')->post(route('admin.permissions.reset-defaults'), ['role_id' => $staff->role_id])
            ->assertSessionHas('success')->assertSessionHasNoErrors();
        $this->assertSame($original, $captain->role->fresh()->permissions->pluck('id')->all());
        $this->assertTrue($staff->role->fresh()->hasPermission('view_residents'));
        $this->assertFalse($staff->role->fresh()->hasPermission('create_users'));
        $this->postJson(route('admin.permissions.reset-defaults'), [])->assertUnprocessable()->assertJsonValidationErrors('role_id');
    }

    public function test_custom_role_without_defaults_is_preserved(): void
    {
        $owner = $this->account('super_admin');
        $custom = $this->account('custom_clerk', ['view_residents']);
        $this->actingAs($owner, 'admin')->postJson(route('admin.permissions.reset-defaults'), ['role_id' => $custom->role_id])
            ->assertUnprocessable()->assertJsonValidationErrors('role_id');
        $this->assertTrue($custom->role->fresh()->hasPermission('view_residents'));
    }

    public function test_admin_names_accept_filipino_letters_and_apostrophes(): void
    {
        $owner = $this->account('super_admin');
        $this->actingAs($owner, 'admin')->post(route('admin.users.store'), [
            'first_name' => 'José', 'middle_initial' => 'ñ', 'last_name' => "D'Peña",
            'username' => 'jose', 'email' => 'jose@example.test', 'password' => 'Password123!', 'role_id' => $owner->role_id,
        ])->assertSessionHasNoErrors()->assertSessionHas('success');
        $this->assertDatabaseHas('admin_users', ['username' => 'jose', 'middle_initial' => 'Ñ', 'last_name' => "D'Peña"]);
    }
}
