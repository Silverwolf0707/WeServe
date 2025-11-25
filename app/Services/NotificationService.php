<?php

namespace App\Services;

use App\Models\UserNotification;
use App\Models\PatientStatusLog;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Create notifications for a status log - NOTIFY ALL USERS (including performer)
     */
    public static function createStatusLogNotifications(PatientStatusLog $statusLog)
    {
        try {
            // Notify ALL users including the one who performed the action
            $usersToNotify = User::pluck('id');
            
            foreach ($usersToNotify as $userId) {
                UserNotification::create([
                    'user_id' => $userId,
                    'status_log_id' => $statusLog->id,
                    'is_read' => false,
                ]);
            }

        } catch (\Exception $e) {
            Log::error("Failed to create notifications: " . $e->getMessage());
        }
    }

    /**
     * Create notifications for new patient records - NOTIFY ALL USERS (including creator)
     */
    public static function createNewPatientNotifications(PatientStatusLog $statusLog)
    {
        try {
            // Notify ALL users including the one who created the patient
            $usersToNotify = User::pluck('id');

            foreach ($usersToNotify as $userId) {
                UserNotification::create([
                    'user_id' => $userId,
                    'status_log_id' => $statusLog->id,
                    'is_read' => false,
                ]);
            }

        } catch (\Exception $e) {
            Log::error("Failed to create new patient notifications: " . $e->getMessage());
        }
    }
}