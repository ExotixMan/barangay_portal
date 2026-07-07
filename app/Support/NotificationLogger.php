<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class NotificationLogger
{
    public static function smsSent(
        string $recipient,
        ?string $message = null,
        ?string $referenceType = null,
        ?int $referenceId = null
    ): void {
        if (! Schema::hasTable('notification_logs')) {
            return;
        }

        DB::table('notification_logs')->insert([
            'channel' => 'sms',
            'recipient' => $recipient,
            'status' => 'sent',
            'message' => $message,
            'error_message' => null,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'sent_by' => auth('admin')->id(),
            'sent_at' => now('Asia/Manila'),
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    public static function smsFailed(
        string $recipient,
        ?string $message = null,
        ?string $errorMessage = null,
        ?string $referenceType = null,
        ?int $referenceId = null
    ): void {
        if (! Schema::hasTable('notification_logs')) {
            return;
        }

        DB::table('notification_logs')->insert([
            'channel' => 'sms',
            'recipient' => $recipient,
            'status' => 'failed',
            'message' => $message,
            'error_message' => $errorMessage,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'sent_by' => auth('admin')->id(),
            'sent_at' => null,
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }
}