<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use SoftDeletes, Notifiable, HasFactory, Auditable;

    public $table = 'users';

    protected $hidden = [
        'remember_token',
        'password',
    ];

    protected $dates = [
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
        'last_login_at', // This is deprecated in Laravel 8+, use $casts instead
    ];

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
        'last_login_at',
        'last_login_ip',
        'status',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getIsAdminAttribute()
    {
        return $this->roles()->where('id', 1)->exists();
    }

    // Remove or fix the getEmailVerifiedAtAttribute accessor as it's causing issues
    // public function getEmailVerifiedAtAttribute($value)
    // {
    //     return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    // }

    // Remove or fix the setEmailVerifiedAtAttribute mutator as it's causing issues
    // public function setEmailVerifiedAtAttribute($value)
    // {
    //     $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    // }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function isOnline()
    {
        return DB::table('sessions')
            ->where('user_id', $this->id)
            ->where('last_activity', '>=', now()->subMinutes(config('session.lifetime'))->timestamp)
            ->exists();
    }

    /**
     * Get the activities for the user.
     */
    public function activities()
    {
        return $this->hasMany(Activity::class, 'causer_id')
            ->where('causer_type', self::class);
    }

    /**
     * Get login activities for the user.
     */
    public function loginActivities()
    {
        return $this->activities()
            ->where('event', 'login')
            ->orWhere('description', 'LIKE', '%logged in%')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get logout activities for the user.
     */
    public function logoutActivities()
    {
        return $this->activities()
            ->where('event', 'logout')
            ->orWhere('description', 'LIKE', '%logged out%')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get failed login activities for the user.
     */
    public function failedLoginActivities()
    {
        return $this->activities()
            ->where('event', 'login_failed')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole($role): bool
    {
        if (is_numeric($role)) {
            return $this->roles()->where('id', $role)->exists();
        }
        return $this->roles()->where('title', $role)->exists();
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('title', $roles)
            ->orWhereIn('id', $roles)
            ->exists();
    }

    /**
     * Record user login activity.
     */
    public function recordLogin($ipAddress, $userAgent)
    {
        // Update user's last login info
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ipAddress,
        ]);

        // Create login activity
        Activity::create([
            'log_name' => 'auth',
            'description' => 'User logged in successfully',
            'event' => 'login',
            'subject_id' => $this->id,
            'subject_type' => self::class,
            'causer_id' => $this->id,
            'causer_type' => self::class,
            'properties' => [
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'login_method' => 'password',
                'user_agent_short' => $this->getShortUserAgent($userAgent),
            ],
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }

    /**
     * Record user logout activity.
     */
    public function recordLogout($ipAddress, $userAgent)
    {
        $lastLogin = $this->last_login_at;
        $sessionDuration = $lastLogin ? now()->diffInMinutes($lastLogin) : null;

        Activity::create([
            'log_name' => 'auth',
            'description' => 'User logged out',
            'event' => 'logout',
            'subject_id' => $this->id,
            'subject_type' => self::class,
            'causer_id' => $this->id,
            'causer_type' => self::class,
            'properties' => [
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'session_duration_minutes' => $sessionDuration,
                'user_agent_short' => $this->getShortUserAgent($userAgent),
            ],
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }

    /**
     * Record failed login attempt.
     */
    public function recordFailedLogin($ipAddress, $userAgent, $emailAttempted = null)
    {
        Activity::create([
            'log_name' => 'auth',
            'description' => 'Failed login attempt',
            'event' => 'login_failed',
            'subject_id' => $this->id,
            'subject_type' => self::class,
            'causer_id' => $this->id,
            'causer_type' => self::class,
            'properties' => [
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'email_attempted' => $emailAttempted ?? $this->email,
                'user_agent_short' => $this->getShortUserAgent($userAgent),
            ],
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }

    /**
     * Get the user's last login activity.
     */
    public function getLastLoginActivity()
    {
        return $this->loginActivities()->first();
    }

    /**
     * Get the user's current session start time.
     */
    public function getCurrentSessionStart()
    {
        return $this->last_login_at;
    }

    /**
     * Check if user is currently active (logged in within last 30 minutes).
     */
    public function isCurrentlyActive(): bool
    {
        return $this->last_login_at &&
            $this->last_login_at->gt(now()->subMinutes(30));
    }

    /**
     * Get short version of user agent for display.
     */
    private function getShortUserAgent($userAgent)
    {
        if (str_contains($userAgent, 'Chrome')) return 'Chrome';
        if (str_contains($userAgent, 'Firefox')) return 'Firefox';
        if (str_contains($userAgent, 'Safari')) return 'Safari';
        if (str_contains($userAgent, 'Edge')) return 'Edge';
        if (str_contains($userAgent, 'Opera')) return 'Opera';
        return 'Unknown';
    }

    /**
     * Get user's login statistics.
     */
    public function getLoginStats()
    {
        $totalLogins = $this->loginActivities()->count();
        $failedLogins = $this->failedLoginActivities()->count();
        $lastMonthLogins = $this->loginActivities()
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        return [
            'total_logins' => $totalLogins,
            'failed_logins' => $failedLogins,
            'last_month_logins' => $lastMonthLogins,
            'success_rate' => $totalLogins > 0 ?
                round((($totalLogins - $failedLogins) / $totalLogins) * 100, 2) : 0,
        ];
    }

    /**
     * Safe date accessor for views
     */
    public function getFormattedLastLoginAtAttribute()
    {
        if (!$this->last_login_at) {
            return null;
        }

        // Ensure it's a Carbon instance
        $date = $this->last_login_at;
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        return $date->format('M j, Y g:i A');
    }

    /**
     * Safe date accessor for email verified at
     */
    public function getFormattedEmailVerifiedAtAttribute()
    {
        if (!$this->email_verified_at) {
            return null;
        }

        // Ensure it's a Carbon instance
        $date = $this->email_verified_at;
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        return $date->format('M j, Y g:i A');
    }
    /**
     * Get all profile images for the user.
     */
    public function profileImages()
    {
        return $this->hasMany(ProfileImage::class);
    }

    /**
     * Get the current profile image.
     */
    public function currentProfileImage()
    {
        return $this->hasOne(ProfileImage::class)->where('is_current', true);
    }

    /**
     * Get the current profile image URL.
     */
    public function getProfileImageUrlAttribute()
    {
        $currentImage = $this->currentProfileImage;
        return $currentImage ? $currentImage->image_url : null;
    }

    /**
     * Get the current profile image path.
     */
    public function getProfileImagePathAttribute()
    {
        $currentImage = $this->currentProfileImage;
        return $currentImage ? $currentImage->image_path : null;
    }
}
