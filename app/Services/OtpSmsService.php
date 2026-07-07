<?php

namespace App\Services;

use App\Models\Residents;
use App\Services\AdminSettingsService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OtpSmsService
{
    public function __construct(private AdminSettingsService $settings)
    {
    }

    public function sendOtp(Residents $resident, int $otp, string $context): bool
    {
        $message = "Your Barangay Portal OTP is: {$otp}";
        $originalNumber = (string) $resident->contact;
        $to = $this->normalizeTo63Format($originalNumber);

        if ($to === null) {
            Log::error('Semaphore: Invalid phone number format', [
                'to' => $originalNumber,
                'resident_id' => $resident->id,
                'context' => $context,
            ]);
            return false;
        }

        $smsConfig = $this->settings->section('sms');
        $apiKey = (string) ($smsConfig['api_key'] ?? '');
        $sender = (string) ($smsConfig['sender_name'] ?? 'SEMAPHORE');
        $endpoint = (string) ($smsConfig['api_base_url'] ?? 'https://api.semaphore.co/api/v4/messages');
        $dailyLimit = max(1, (int) ($smsConfig['daily_limit'] ?? 5));

        if ($apiKey === '') {
            Log::error('Semaphore: Missing SMS_API_KEY', [
                'resident_id' => $resident->id,
                'context' => $context,
            ]);
            return false;
        }

        $limitKey = 'sms:otp:' . $to . ':' . now()->format('Y-m-d');
        $currentCount = (int) Cache::get($limitKey, 0);
        if ($currentCount >= $dailyLimit) {
            Log::warning('OTP SMS daily limit reached', [
                'to' => $to,
                'context' => $context,
                'resident_id' => $resident->id,
                'daily_limit' => $dailyLimit,
            ]);
            return false;
        }

        try {
            $response = Http::asForm()
                ->timeout(10)
                ->post($endpoint, [
                    'apikey' => $apiKey,
                    'number' => $to,
                    'message' => $message,
                    'sendername' => $sender,
                ]);

            $payload = $response->json();
            $status = is_array($payload) && isset($payload[0]['status']) ? (string) $payload[0]['status'] : null;

            if ($response->successful() && $status === 'Queued') {
                Cache::put($limitKey, $currentCount + 1, now()->endOfDay());
                $this->settings->incrementUsage('sms');
                Log::info('OTP sent via Semaphore', [
                    'to' => $to,
                    'context' => $context,
                    'resident_id' => $resident->id,
                ]);
                return true;
            }

            Log::error('Failed to send OTP via Semaphore', [
                'to' => $to,
                'context' => $context,
                'resident_id' => $resident->id,
                'status_code' => $response->status(),
                'response' => $response->body(),
            ]);
            return false;
        } catch (\Throwable $e) {
            Log::error('Failed to send OTP via Semaphore', [
                'error' => $e->getMessage(),
                'resident_id' => $resident->id,
                'contact' => $originalNumber,
                'context' => $context,
            ]);
            return false;
        }
    }

    private function normalizeTo63Format(string $number): ?string
    {
        $digits = preg_replace('/\D+/', '', $number);

        if (preg_match('/^09\d{9}$/', $digits)) {
            return '63' . substr($digits, 1);
        }

        if (preg_match('/^9\d{9}$/', $digits)) {
            return '63' . $digits;
        }

        if (preg_match('/^63\d{10}$/', $digits)) {
            return $digits;
        }

        return null;
    }
}
