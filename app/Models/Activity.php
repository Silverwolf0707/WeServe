<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends Model
{
    protected $table = 'activity_log';

    protected $fillable = [
        'log_name',
        'description',
        'event',
        'subject_id',
        'subject_type',
        'causer_id',
        'causer_type',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the subject of the activity.
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user that caused the activity.
     */
    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include login activities.
     */
    public function scopeLogins($query)
    {
        return $query->where('event', 'login')
                    ->orWhere('description', 'LIKE', '%logged in%');
    }

    /**
     * Scope a query to only include logout activities.
     */
    public function scopeLogouts($query)
    {
        return $query->where('event', 'logout')
                    ->orWhere('description', 'LIKE', '%logged out%');
    }

    /**
     * Scope a query to only include activities for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('causer_id', $userId)
                    ->where('causer_type', User::class);
    }

    /**
     * Scope a query to only include recent activities.
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}