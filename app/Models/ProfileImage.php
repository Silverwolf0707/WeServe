<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProfileImage extends Model
{
    // Specify the table name since it's different from the model name
    protected $table = 'user_profile_images';
    
    protected $fillable = [
        'user_id',
        'image_path',
        'is_current',
        'description'
    ];

    protected $casts = [
        'is_current' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor for image URL (served through a secure route)
    public function getImageUrlAttribute()
    {
        if (!$this->image_path) {
            return null;
        }
        
        // Alternative 1: Try to generate the URL directly
        // Since you're in admin routes, prefix with admin
        return url("/admin/profile-image/{$this->id}");
        
        // Alternative 2: Use route helper if it works
        // try {
        //     return route('profile.image.show', $this->id);
        // } catch (\Exception $e) {
        //     // Fallback to direct URL
        //     return url("/admin/profile-image/{$this->id}");
        // }
    }

    // Helper method to get the actual file path
    public function getFilePath()
    {
        return storage_path('app/private/' . $this->image_path);
    }
    
    // Check if file exists in storage
    public function fileExists()
    {
        return Storage::disk('private')->exists($this->image_path);
    }
}