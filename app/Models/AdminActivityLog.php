<?php
// app/Models/UserActivityLog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AdminActivityLog extends Model
{
    use HasFactory;

    private const DUPLICATE_GUARD_SECONDS = 2;

    protected $table = 'admin_activity_logs';

    protected $fillable = [
        'user_id',
        'action',
        'module',
        'details',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'details' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $log) {
            if (self::shouldSuppressBackgroundViewLog($log)) {
                return false;
            }

            $fingerprint = self::buildDuplicateFingerprint($log);
            $cacheKey = 'admin_activity_log:dedupe:' . sha1($fingerprint);

            // Cache::add is atomic, so concurrent duplicate writes are rejected.
            return Cache::add($cacheKey, true, now()->addSeconds(self::DUPLICATE_GUARD_SECONDS));
        });
    }

    private static function shouldSuppressBackgroundViewLog(self $log): bool
    {
        if (!app()->bound('request')) {
            return false;
        }

        $request = request();
        if (!$request || !$request->isMethod('get')) {
            return false;
        }

        if ($request->header('X-Auto-Sync') !== '1') {
            return false;
        }

        $action = strtolower((string) $log->action);
        return str_contains($action, 'view') || str_contains($action, 'read');
    }

    private static function buildDuplicateFingerprint(self $log): string
    {
        $details = self::normalizeDetails($log->details);

        return json_encode([
            'user_id' => $log->user_id,
            'action' => strtoupper((string) $log->action),
            'module' => (string) $log->module,
            'ip_address' => (string) $log->ip_address,
            'user_agent' => (string) $log->user_agent,
            'details' => $details,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    private static function normalizeDetails($details): array
    {
        if (is_string($details)) {
            $decoded = json_decode($details, true);
            $details = is_array($decoded) ? $decoded : [];
        }

        if (!is_array($details)) {
            return [];
        }

        self::removeVolatileKeys($details);
        ksort($details);

        return $details;
    }

    private static function removeVolatileKeys(array &$data): void
    {
        foreach ($data as $key => &$value) {
            if (is_array($value)) {
                self::removeVolatileKeys($value);
                ksort($value);
            }

            if (in_array((string) $key, ['timestamp', 'created_at', 'updated_at'], true)) {
                unset($data[$key]);
            }
        }
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(AdminUser::class, 'user_id');
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByModule($query, $module)
    {
        return $query->where('module', $module);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    // Accessors
    public function getFormattedDetailsAttribute()
    {
        if (is_array($this->details)) {
            return json_encode($this->details, JSON_PRETTY_PRINT);
        }
        return $this->details;
    }

    public function getActionBadgeClassAttribute()
    {
        return match($this->action) {
            'LOGIN', 'LOGOUT' => 'bg-info',
            'CREATE' => 'bg-success',
            'UPDATE' => 'bg-warning',
            'DELETE' => 'bg-danger',
            'REDIRECT' => 'bg-secondary',
            default => 'bg-primary'
        };
    }
}