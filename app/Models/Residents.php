<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Services\EmailDeliveryService;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class Residents extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, MustVerifyEmailTrait, SoftDeletes;

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
        'phone_verified' => 'boolean',
        'valid_id_verified' => 'boolean',
        'birthdate' => 'date',
        'phone_otp_expires_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'valid_id_verified_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

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
        $verificationUrl = URL::temporarySignedRoute(
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
        $url = url(route('resident.password.reset', [
            'token' => $token,
            'email' => $this->email,
        ], false));

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
}
