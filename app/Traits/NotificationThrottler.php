<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;


trait NotificationThrottler
{
    
    protected $throttleConfig = [
        'email' => [
            'max_per_minute' => 10,      
            'max_per_hour' => 50,          
            'cooldown_seconds' => 0,       
            'enabled' => true,
        ],
        'sms' => [
            'max_per_minute' => 5,       
            'max_per_hour' => 20,          
            'cooldown_seconds' => 30,      
            'enabled' => true,
        ],
        'notification' => [
            'max_per_minute' => 15,      
            'max_per_hour' => 60,          
            'cooldown_seconds' => 0,       
            'enabled' => true,
        ]
    ];

    
    public function canSendNotification($type, $recipient, $channel = null)
    {
        
        if (!isset($this->throttleConfig[$type]) || !$this->throttleConfig[$type]['enabled']) {
            return true;
        }

        $config = $this->throttleConfig[$type];
        $userId = auth('admin')->id() ?? 'unknown';
        
        
        if (!$this->checkRateLimit($type, $userId, 'minute', $config['max_per_minute'])) {
            Log::warning("Notification throttled - per minute limit exceeded", [
                'type' => $type,
                'user_id' => $userId,
                'recipient' => $recipient,
                'limit' => $config['max_per_minute']
            ]);
            return false;
        }

        
        if (!$this->checkRateLimit($type, $userId, 'hour', $config['max_per_hour'])) {
            Log::warning("Notification throttled - per hour limit exceeded", [
                'type' => $type,
                'user_id' => $userId,
                'recipient' => $recipient,
                'limit' => $config['max_per_hour']
            ]);
            return false;
        }

        
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

    
    public function recordNotification($type, $recipient, $channel = null)
    {
        $userId = auth('admin')->id() ?? 'unknown';
        
        
        $minuteKey = "notif_throttle:{$type}:{$userId}:minute";
        Cache::increment($minuteKey, 1, 60); 

        
        $hourKey = "notif_throttle:{$type}:{$userId}:hour";
        Cache::increment($hourKey, 1, 3600); 

        
        $recipientKey = "notif_throttle:{$type}:recipient:{$recipient}";
        Cache::put($recipientKey, now()->timestamp, now()->addHours(1));

        
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

    
    protected function checkRateLimit($type, $userId, $window, $limit)
    {
        $key = "notif_throttle:{$type}:{$userId}:{$window}";
        $current = Cache::get($key, 0);
        return $current < $limit;
    }

    
    protected function checkRecipientCooldown($type, $recipient, $cooldownSeconds)
    {
        $key = "notif_throttle:{$type}:recipient:{$recipient}";
        $lastSentTime = Cache::get($key);
        
        if (!$lastSentTime) {
            return true; 
        }

        $elapsedSeconds = now()->timestamp - $lastSentTime;
        return $elapsedSeconds >= $cooldownSeconds;
    }

    
    protected function checkDuplication($type, $recipient, $channel = null)
    {
        if (!$channel) {
            return true; 
        }

        $key = "notif_dedup:{$type}:{$recipient}:{$channel}";
        return !Cache::has($key); 
    }

    
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

    
    public function resetThrottleLimit($type = null)
    {
        $userId = auth('admin')->id() ?? 'unknown';
        
        if ($type) {
            Cache::forget("notif_throttle:{$type}:{$userId}:minute");
            Cache::forget("notif_throttle:{$type}:{$userId}:hour");
        } else {
            
            foreach (array_keys($this->throttleConfig) as $key) {
                Cache::forget("notif_throttle:{$key}:{$userId}:minute");
                Cache::forget("notif_throttle:{$key}:{$userId}:hour");
            }
        }
    }

    
    public function setThrottleConfig($type, $config)
    {
        if (isset($this->throttleConfig[$type])) {
            $this->throttleConfig[$type] = array_merge($this->throttleConfig[$type], $config);
        }
    }

    
    public function disableThrottle($type)
    {
        if (isset($this->throttleConfig[$type])) {
            $this->throttleConfig[$type]['enabled'] = false;
        }
    }

    
    public function enableThrottle($type)
    {
        if (isset($this->throttleConfig[$type])) {
            $this->throttleConfig[$type]['enabled'] = true;
        }
    }
}
