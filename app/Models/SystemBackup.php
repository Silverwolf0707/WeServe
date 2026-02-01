<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemBackup extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'filename',
        'path',
        'size',
        'type',
        'description',
        'created_by',
        'restored_at',
        'restored_by'
    ];

    protected $casts = [
        'restored_at' => 'datetime',
        'size' => 'integer',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function restorer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'restored_by');
    }
}