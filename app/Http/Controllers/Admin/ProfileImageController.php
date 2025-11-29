<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfileImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfileImageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $user = Auth::user();

            // Upload image
            $path = $request->file('profile_image')->store('profile-images', 'public');

            // Deactivate current profile image
            ProfileImage::where('user_id', $user->id)
                        ->where('is_current', true)
                        ->update(['is_current' => false]);

            // Create new profile image record
            $profileImage = ProfileImage::create([
                'user_id' => $user->id,
                'image_path' => $path,
                'is_current' => true,
                'description' => 'Profile image uploaded by user',
            ]);

            return response()->json([
                'success' => true,
                'image_url' => $profileImage->image_url,
                'message' => 'Profile image updated successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Profile image upload failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload profile image. Please try again.'
            ], 500);
        }
    }

    public function destroy(ProfileImage $profileImage)
    {
        // Check if user owns this image
        if ($profileImage->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        try {
            // Delete physical file
            Storage::disk('public')->delete($profileImage->image_path);

            // Delete database record
            $profileImage->delete();

            // If this was the current image, set another one as current
            if ($profileImage->is_current) {
                $newCurrent = ProfileImage::where('user_id', Auth::id())
                    ->latest()
                    ->first();
                
                if ($newCurrent) {
                    $newCurrent->update(['is_current' => true]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile image deleted successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Profile image deletion failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete profile image. Please try again.'
            ], 500);
        }
    }

    public function setCurrent(ProfileImage $profileImage)
    {
        // Check if user owns this image
        if ($profileImage->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        try {
            // Deactivate all current images
            ProfileImage::where('user_id', Auth::id())
                ->where('is_current', true)
                ->update(['is_current' => false]);

            // Set this image as current
            $profileImage->update(['is_current' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Profile image set as current!'
            ]);

        } catch (\Exception $e) {
            Log::error('Set current profile image failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to set profile image. Please try again.'
            ], 500);
        }
    }
}