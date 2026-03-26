<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * NotificationThrottler Trait
 * 
 * Provides multiple strategies to prevent notification spam:
 * 1. Rate limiting - max notifications per time window
 * 2. Deduplication - prevent duplicate notifications to same recipient
 * 3. Cooldown - enforce minimum interval between notifications
 * 4. Batch limiting - max total notifications per admin per hour
 * 
 * Usage in controller:
 *   if ($this->canSendNotification('email', $recipient, 'email_channel')) {
 *       // send notification
 *       $this->recordNotification('email', $recipient, 'email_channel');
 *   } else {
 *       return redirect()->back()->with('error', 'Too many notifications. Please try again later.');
 *   }
 */
trait NotificationThrottler
{
    /**
     * Configuration for throttling (can be overridden in controller)
     */
    protected $throttleConfig = [
        'email' => [
            'max_per_minute' => 10,      // Max emails per minute
            'max_per_hour' => 50,          // Max emails per hour
            'cooldown_seconds' => 0,       // Min seconds between emails to same recipient
            'enabled' => true,
        ],
        'sms' => [
            'max_per_minute' => 5,       // Max SMS per minute
            'max_per_hour' => 20,          // Max SMS per hour
            'cooldown_seconds' => 30,      // Min seconds between SMS to same recipient
            'enabled' => true,
        ],
        'notification' => [
            'max_per_minute' => 15,      // Max notifications per minute
            'max_per_hour' => 60,          // Max notifications per hour
            'cooldown_seconds' => 0,       // Min seconds between notifications
            'enabled' => true,
        ]
    ];

    /**
     * Check if notification can be sent without exceeding throttle limits
     * 
     * @param string $type notification type (email, sms, notification, etc.)
     * @param string $recipient recipient identifier (email, phone, user_id)
     * @param string $channel optional channel identifier
     * @return bool true if notification can be sent, false if throttled
     */
    public function canSendNotification($type, $recipient, $channel = null)
    {
        // Check if throttling is enabled for this type
        if (!isset($this->throttleConfig[$type]) || !$this->throttleConfig[$type]['enabled']) {
            return true;
        }

        $config = $this->throttleConfig[$type];
        $userId = auth('admin')->id() ?? 'unknown';
        
        // Check per-minute rate limit
        if (!$this->checkRateLimit($type, $userId, 'minute', $config['max_per_minute'])) {
            Log::warning("Notification throttled - per minute limit exceeded", [
                'type' => $type,
                'user_id' => $userId,
                'recipient' => $recipient,
                'limit' => $config['max_per_minute']
            ]);
            return false;
        }

        // Check per-hour rate limit
        if (!$this->checkRateLimit($type, $userId, 'hour', $config['max_per_hour'])) {
            Log::warning("Notification throttled - per hour limit exceeded", [
                'type' => $type,
                'user_id' => $userId,
                'recipient' => $recipient,
                'limit' => $config['max_per_hour']
            ]);
            return false;
        }

        // Check recipient cooldown period (prevent spam to same recipient)
        if ($config['cooldown_seconds'] > 0) {
            if (!$this->checkRecipientCooldown($type, $recipient, $config['cooldown_seconds'])) {
                Log::warning("Notification throttled - recipient cooldown active", [
                    'type' => $type,
                    'recipient' => $recipient,
                    'cooldown' => $config['cooldown_seconds']
                ]);
                return false;
            }
        }

        // Check for duplicate/near-duplicate notifications
        if (!$this->checkDuplication($type, $recipient, $channel)) {
            Log::warning("Notification throttled - duplicate detected", [
                'type' => $type,
                'recipient' => $recipient,
                'channel' => $channel
            ]);
            return false;
        }

        return true;
    }

    /**
     * Record that a notification was sent (updates throttle counters)
     * 
     * @param string $type notification type
     * @param string $recipient recipient identifier
     * @param string $channel optional channel identifier
     */
    public function recordNotification($type, $recipient, $channel = null)
    {
        $userId = auth('admin')->id() ?? 'unknown';
        
        // Increment per-minute counter
        $minuteKey = "notif_throttle:{$type}:{$userId}:minute";
        Cache::increment($minuteKey, 1, 60); // 60 second expiry

        // Increment per-hour counter
        $hourKey = "notif_throttle:{$type}:{$userId}:hour";
        Cache::increment($hourKey, 1, 3600); // 3600 second expiry

        // Record recipient last sent time
        $recipientKey = "notif_throttle:{$type}:recipient:{$recipient}";
        Cache::put($recipientKey, now()->timestamp, now()->addHours(1));

        // Record recent notification for deduplication
        if ($channel) {
            $dedupKey = "notif_dedup:{$type}:{$recipient}:{$channel}";
            Cache::put($dedupKey, now()->timestamp, now()->addMinutes(5));
        }

        Log::info("Notification recorded in throttle system", [
            'type' => $type,
            'recipient' => $recipient,
            'user_id' => $userId
        ]);
    }

    /**
     * Check per-minute or per-hour rate limit
     */
    protected function checkRateLimit($type, $userId, $window, $limit)
    {
        $key = "notif_throttle:{$type}:{$userId}:{$window}";
        $current = Cache::get($key, 0);
        return $current < $limit;
    }

    /**
     * Check if recipient is still in cooldown period
     */
    protected function checkRecipientCooldown($type, $recipient, $cooldownSeconds)
    {
        $key = "notif_throttle:{$type}:recipient:{$recipient}";
        $lastSentTime = Cache::get($key);
        
        if (!$lastSentTime) {
            return true; // No previous notification, can send
        }

        $elapsedSeconds = now()->timestamp - $lastSentTime;
        return $elapsedSeconds >= $cooldownSeconds;
    }

    /**
     * Check for duplicate/near-duplicate notifications
     */
    protected function checkDuplication($type, $recipient, $channel = null)
    {
        if (!$channel) {
            return true; // No channel specified, skip dedup check
        }

        $key = "notif_dedup:{$type}:{$recipient}:{$channel}";
        return !Cache::has($key); // Return true if NOT a duplicate (key doesn't exist)
    }

    /**
     * Get current throttle status for admin user
     */
    public function getThrottleStatus()
    {
        $userId = auth('admin')->id() ?? 'unknown';
        $status = [];

        foreach ($this->throttleConfig as $type => $config) {
            if (!$config['enabled']) {
                continue;
            }

            $minuteCount = Cache::get("notif_throttle:{$type}:{$userId}:minute", 0);
            $hourCount = Cache::get("notif_throttle:{$type}:{$userId}:hour", 0);

            $status[$type] = [
                'per_minute' => [
                    'current' => $minuteCount,
                    'limit' => $config['max_per_minute'],
                    'remaining' => max(0, $config['max_per_minute'] - $minuteCount),
                    'exceeded' => $minuteCount >= $config['max_per_minute']
                ],
                'per_hour' => [
                    'current' => $hourCount,
                    'limit' => $config['max_per_hour'],
                    'remaining' => max(0, $config['max_per_hour'] - $hourCount),
                    'exceeded' => $hourCount >= $config['max_per_hour']
                ],
            ];
        }

        return $status;
    }

    /**
     * Reset throttle limits (admin only, for testing)
     */
    public function resetThrottleLimit($type = null)
    {
        $userId = auth('admin')->id() ?? 'unknown';
        
        if ($type) {
            Cache::forget("notif_throttle:{$type}:{$userId}:minute");
            Cache::forget("notif_throttle:{$type}:{$userId}:hour");
        } else {
            // Reset all types
            foreach (array_keys($this->throttleConfig) as $key) {
                Cache::forget("notif_throttle:{$key}:{$userId}:minute");
                Cache::forget("notif_throttle:{$key}:{$userId}:hour");
            }
        }
    }

    /**
     * Configure throttle limits (can be called in controller constructor)
     * 
     * @param string $type notification type
     * @param array $config configuration array with max_per_minute, max_per_hour, cooldown_seconds
     */
    public function setThrottleConfig($type, $config)
    {
        if (isset($this->throttleConfig[$type])) {
            $this->throttleConfig[$type] = array_merge($this->throttleConfig[$type], $config);
        }
    }

    /**
     * Disable throttling for specific type (use with caution)
     */
    public function disableThrottle($type)
    {
        if (isset($this->throttleConfig[$type])) {
            $this->throttleConfig[$type]['enabled'] = false;
        }
    }

    /**
     * Enable throttling for specific type
     */
    public function enableThrottle($type)
    {
        if (isset($this->throttleConfig[$type])) {
            $this->throttleConfig[$type]['enabled'] = true;
        }
    }
}
