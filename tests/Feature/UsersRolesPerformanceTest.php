<?php

namespace Tests\Feature;

use App\Models\AdminPermission;
use App\Models\AdminRole;
use App\Models\AdminUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UsersRolesPerformanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_permission_checks_and_full_page_have_bounded_database_work(): void
    {
        Cache::flush();
        $names = ['view_users', 'view_roles', 'view_permissions', 'update_roles', 'manage_role_permissions', 'manage_user_permissions', 'update_users'];
        for ($i = 0; $i < 120; $i++) {
            $names[] = 'test_permission_'.$i;
        }
        foreach ($names as $name) {
            AdminPermission::create(['name' => $name, 'display_name' => $name, 'module' => 'users']);
        }
        $permissions = AdminPermission::pluck('id');
        for ($i = 0; $i < 12; $i++) {
            $role = AdminRole::create(['name' => 'review_role_'.$i, 'display_name' => 'Review Role '.$i]);
            $role->permissions()->sync($permissions);
            $actor = AdminUser::create([
                'first_name' => 'Review', 'last_name' => 'Administrator', 'username' => 'review_'.$i,
                'email' => 'review_'.$i.'@example.test', 'password' => bcrypt('Password123!'),
                'role_id' => $role->id, 'status' => 'active',
            ]);
        }
        $this->actingAs($actor->fresh(), 'admin');
        DB::enableQueryLog();
        $response = $this->get(route('admin.users.index'));
        $queries = DB::getQueryLog();
        DB::disableQueryLog();
        $response->assertOk()->assertSee('Review Role 11');
        $this->assertLessThan(700000, strlen($response->getContent()), 'Permission editors should load on demand rather than repeat thousands of hidden checkboxes.');
        $this->assertLessThan(70, count($queries), 'Repeated permissions and role members must not issue a query per checkbox.');
        DB::flushQueryLog();
        DB::enableQueryLog();
        $editor = $this->get(route('admin.users.dialog', ['type' => 'user-permissions', 'id' => $actor->id]));
        $editorQueries = DB::getQueryLog();
        DB::disableQueryLog();
        $editor->assertOk();
        $this->assertSame(count($names), substr_count($editor->getContent(), ' checked'));
        $this->assertLessThan(15, count($editorQueries), 'Loading an editor must also reuse its permissions instead of querying per checkbox.');
    }

    public function test_permission_changes_are_visible_on_the_next_request(): void
    {
        $role = AdminRole::create(['name' => 'clerk']);
        $permission = AdminPermission::create(['name' => 'view_users', 'display_name' => 'View users', 'module' => 'users']);
        $role->permissions()->attach($permission);
        $actor = AdminUser::create([
            'first_name' => 'Test', 'last_name' => 'Clerk', 'username' => 'clerk', 'email' => 'clerk@example.test',
            'password' => bcrypt('Password123!'), 'role_id' => $role->id, 'status' => 'active',
        ]);
        $this->actingAs($actor, 'admin')->get(route('admin.users.index'))->assertOk();
        $role->permissions()->detach();
        $this->actingAs($actor->fresh(), 'admin')->get(route('admin.users.index'))->assertForbidden();
    }

    public function test_permission_editors_load_on_demand_and_enforce_permissions(): void
    {
        $ownerRole = AdminRole::create(['name' => 'super_admin']);
        $owner = AdminUser::create([
            'first_name' => 'Test', 'last_name' => 'Owner', 'username' => 'owner', 'email' => 'owner@example.test',
            'password' => bcrypt('Password123!'), 'role_id' => $ownerRole->id, 'status' => 'active',
        ]);
        $role = AdminRole::create(['name' => 'clerk']);
        $permission = AdminPermission::create(['name' => 'view_users', 'display_name' => 'View users', 'module' => 'users']);
        $role->permissions()->attach($permission);
        $clerk = AdminUser::create([
            'first_name' => 'Test', 'last_name' => 'Clerk', 'username' => 'clerk', 'email' => 'clerk@example.test',
            'password' => bcrypt('Password123!'), 'role_id' => $role->id, 'status' => 'active',
        ]);
        $this->actingAs($owner, 'admin')->get(route('admin.users.index'))->assertOk()
            ->assertDontSee('id="userPermissionsForm'.$clerk->id.'"', false);
        $this->get(route('admin.users.dialog', ['type' => 'user-permissions', 'id' => $clerk->id]))
            ->assertOk()->assertSee('id="userPermissionsForm'.$clerk->id.'"', false)->assertSee('View users');
        $this->get(route('admin.users.dialog', ['type' => 'role-edit', 'id' => $role->id]))
            ->assertOk()->assertSee('id="editRoleForm'.$role->id.'"', false);
        $this->actingAs($clerk, 'admin')->get(route('admin.users.dialog', ['type' => 'user-permissions', 'id' => $owner->id]))->assertForbidden();
        $this->get(route('admin.users.dialog', ['type' => 'role-edit', 'id' => $role->id]))->assertForbidden();
    }

    public function test_failed_role_editor_keeps_inline_errors_and_old_values(): void
    {
        $role = AdminRole::create(['name' => 'super_admin']);
        $owner = AdminUser::create([
            'first_name' => 'Test', 'last_name' => 'Owner', 'username' => 'owner', 'email' => 'owner@example.test',
            'password' => bcrypt('Password123!'), 'role_id' => $role->id, 'status' => 'active',
        ]);
        $custom = AdminRole::create(['name' => 'custom']);
        $this->actingAs($owner, 'admin')->from(route('admin.users.index'))->put(route('admin.roles.update', $custom), ['name' => 'UPPERCASE'])
            ->assertSessionHasErrors('name');
        $this->get(route('admin.users.index'))->assertOk()->assertSee('value="UPPERCASE"', false)
            ->assertSee('id="editRoleForm'.$custom->id.'"', false);
    }
}
