<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel(
    'admins.{adminId}',
    function ($admin, int $adminId): bool {
        return (int) $admin->getKey() === $adminId;
    },
    ['guards' => ['admin']]
);
