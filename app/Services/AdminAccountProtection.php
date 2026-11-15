<?php

namespace App\Services;

use App\Models\AdminRole;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminAccountProtection
{
    public function authorize(?AdminUser $target = null, ?int $roleId = null): void
    {
        $actor = Auth::guard('admin')->user();
        $assignsOwner = $roleId && AdminRole::whereKey($roleId)->where('name', 'super_admin')->exists();
        abort_if(! $actor?->isSuperAdmin() && ($target?->isSuperAdmin() || $assignsOwner), 403,
            'Only a super administrator can manage super-administrator accounts.');
    }

    // Call inside the same transaction as the role/status change. Locking the
    // owner role serializes competing demotions on PostgreSQL.
    public function ensureOwnerRemains(AdminUser $target, ?int $roleId, string $status): void
    {
        $ownerRole = AdminRole::where('name', 'super_admin')->lockForUpdate()->first();
        if (! $ownerRole || (int) $target->role_id !== (int) $ownerRole->id || $target->status !== 'active') {
            return;
        }
        if ($roleId === (int) $ownerRole->id && $status === 'active') {
            return;
        }
        if (! AdminUser::where('role_id', $ownerRole->id)->where('status', 'active')->whereKeyNot($target->id)->exists()) {
            throw ValidationException::withMessages(['status' => 'Keep at least one active super administrator. Add another active owner before changing this account.']);
        }
    }
}
