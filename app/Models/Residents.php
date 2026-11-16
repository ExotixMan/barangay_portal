<?php

namespace App\Models;

use App\Services\EmailDeliveryService;
use Carbon\Carbon;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class Residents extends Authenticatable implements MustVerifyEmail
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory, MustVerifyEmailTrait, Notifiable, SoftDeletes;

    protected $table = 'residents';

    protected $primaryKey = 'id';

    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'suffix',
        'email',
        'address',
        'birthdate',
        'gender',
        'civil_status',
        'place_of_birth',
        'contact',
        'username',
        'password',

        'valid_id',
        'valid_id_back',
        'valid_id_selfie',

        'is_pwd',
        'is_student',
        'pwd_status',
        'pwd_id_front',
        'pwd_id_back',
        'pwd_verified_at',
        'pwd_verified_by',
        'pwd_rejection_reason',
        'student_status',
        'student_proof',
        'student_verified_at',
        'student_verified_by',
        'student_rejection_reason',

        'valid_id_verified',
        'valid_id_status',
        'valid_id_verified_at',
        'valid_id_verified_by',
        'valid_id_rejection_reason',

        'profile_photo',
        'phone_otp',
        'phone_otp_expires_at',
        'phone_verified',
        'email_verified_at',
    ];

    protected $casts = [
        'phone_verified' => \App\Casts\DatabaseBoolean::class,
        'valid_id_verified' => \App\Casts\DatabaseBoolean::class,
        'is_pwd' => \App\Casts\DatabaseBoolean::class,
        'is_student' => \App\Casts\DatabaseBoolean::class,
        'birthdate' => 'date',
        'phone_otp_expires_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'valid_id_verified_at' => 'datetime',
        'pwd_verified_at' => 'datetime',
        'student_verified_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function setUsernameAttribute($value): void
    {
        $this->attributes['username'] = mb_strtolower(trim((string) $value));
    }

    public function setEmailAttribute($value): void
    {
        $this->attributes['email'] = mb_strtolower(trim((string) $value));
    }

    public function getFullNameAttribute()
    {
        $parts = array_filter([
            $this->firstname,
            $this->middlename,
            $this->lastname,
            $this->suffix,
        ]);

        return implode(' ', $parts);
    }

    public function getValidIdStatusLabelAttribute()
    {
        return match ($this->valid_id_status) {
            'verified' => 'Verified',
            'rejected' => 'Rejected',
            'for_verification' => 'For Verification',
            default => 'Not Submitted',
        };
    }

    public function getHasCompleteValidIdAttribute()
    {
        return ! empty($this->valid_id) && ! empty($this->valid_id_back);
    }

    public function sendEmailVerificationNotification()
    {
        $verificationUrl = \App\Support\CanonicalUrl::generator()->temporarySignedRoute(
            'verification.verify',
            SupportCarbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $this->getKey(),
                'hash' => sha1($this->getEmailForVerification()),
            ]
        );

        app(EmailDeliveryService::class)->sendView(
            $this->getEmailForVerification(),
            'Verify Your Email – Barangay Hulong Duhat',
            'email.verify-email',
            [
                'url' => $verificationUrl,
                'name' => $this->firstname,
                'expireIn' => Config::get('auth.verification.expire', 60),
            ]
        );
    }

    public function sendPasswordResetNotification($token)
    {
        $url = \App\Support\CanonicalUrl::generator()->route('resident.password.reset', [
            'token' => $token,
            'email' => $this->email,
        ]);

        app(EmailDeliveryService::class)->sendView(
            $this->email,
            'Reset Your Password – Barangay Hulong Duhat',
            'email.reset-password',
            [
                'url' => $url,
                'name' => $this->firstname,
            ]
        );
    }

    public function getAgeAttribute()
    {
        if (! $this->birthdate) {
            return null;
        }

        return Carbon::parse($this->birthdate)->age;
    }

    public function getIsSeniorAttribute(): bool
    {
        return $this->age !== null && $this->age >= 60;
    }

    public function getPwdStatusLabelAttribute(): string
    {
        return match ($this->pwd_status) {
            'verified' => 'Verified',
            'pending' => 'Pending Verification',
            'rejected' => 'Rejected',
            default => 'Not Declared',
        };
    }

    public function getStudentStatusLabelAttribute(): string
    {
        return match ($this->student_status) {
            'verified' => 'Verified',
            'pending' => 'Pending Verification',
            'rejected' => 'Rejected',
            default => 'Not Declared',
        };
    }

    public function activities()
    {
        return $this->hasMany(ResidentActivity::class);
    }

    public function profileCorrectionConcerns()
    {
        return $this->hasMany(
            ResidentProfileCorrectionConcern::class,
            'resident_id',
            'id'
        )->latest();
    }

    /**
     * Backward-compatible residency verification flag.
     * The current workflow verifies residency through the resident's Valid ID.
     */
    public function getResidencyVerifiedAttribute(): bool
    {
        return (bool) ($this->attributes['residency_verified'] ?? $this->valid_id_verified ?? false);
    }
}
