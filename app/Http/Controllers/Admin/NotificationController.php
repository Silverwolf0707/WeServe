<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get unread notifications count
     */
    public function getUnreadCount()
    {
        $count = UserNotification::where('user_id', Auth::id())
            ->unread()
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get recent notifications with formatted data
     */
        public function getNotifications(Request $request)
    {
        $departmentFilter = $request->get('department', 'all');
        
        $notifications = UserNotification::with([
            'statusLog.patient',
            'statusLog.user'
        ])
            ->where('user_id', Auth::id())
            ->recent(30)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($notification) {
                $statusLog = $notification->statusLog;
                
                if (!$statusLog) {
                    return null;
                }
                
                $notificationData = $statusLog->getNotificationData();
                $userName = $statusLog->user->name ?? 'System';
                
                return [
                    'id' => $notification->id,
                    'type' => $notificationData['type'],
                    'title' => $notificationData['title'],
                    'message' => $notificationData['message'],
                    'patient_id' => $notificationData['patient_id'],
                    'control_number' => $notificationData['control_number'],
                    'patient_name' => $notificationData['patient_name'],
                    'status' => $notificationData['status'],
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at,
                    'read_at' => $notification->read_at,
                    'user' => $statusLog->user,
                    'user_name' => $userName,
                    'icon' => self::getNotificationIcon($statusLog->status),
                    'icon_color' => self::getNotificationIconColor($statusLog->status),
                    'department' => self::getDepartmentByStatus($statusLog->status),
                    'time_ago' => $notification->created_at->diffForHumans(),
                ];
            })
            ->filter()
            ->values();

        // Apply department filter
        if ($departmentFilter !== 'all') {
            $notifications = $notifications->filter(function ($notification) use ($departmentFilter) {
                return $notification['department'] === $departmentFilter;
            })->values();
        }

        return response()->json($notifications);
    }
        /**
     * Get department by status
     */
    private static function getDepartmentByStatus($status)
    {
        $departmentMapping = [
            // CSWD Office
            'Processing' => 'CSWD Office',
            'Processing[ROLLED BACK]' => 'CSWD Office',
            'Rejected' => 'CSWD Office',
            
            // Mayor's Office
            'Submitted' => "Mayor's Office",
            'Submitted[Emergency]' => "Mayor's Office",
            'Submitted[ROLLED BACK]' => "Mayor's Office",
            
            // Budget Office
            'Approved' => 'Budget Office',
            'Approved[ROLLED BACK]' => 'Budget Office',
            
            // Accounting Office
            'Budget Allocated' => 'Accounting Office',
            'Budget Allocated[ROLLED BACK]' => 'Accounting Office',
            
            // Treasury Office
            'DV Submitted' => 'Treasury Office',
            'DV Submitted[ROLLED BACK]' => 'Treasury Office',
            
            // Default for other statuses
            'Disbursed' => 'Treasury Office',
            'Ready for Disbursement' => 'Treasury Office',
        ];

        return $departmentMapping[$status] ?? 'General';
    }

    /**
     * Get notification icon based on status
     */
    private static function getNotificationIcon($status)
    {
        $icons = [
            'Processing' => 'fas fa-file-medical',
            'Submitted' => 'fas fa-paper-plane',
            'Submitted[Emergency]' => 'fas fa-exclamation-triangle',
            'Approved' => 'fas fa-check-circle',
            'Rejected' => 'fas fa-times-circle',
            'Budget Allocated' => 'fas fa-money-bill-wave',
            'DV Submitted' => 'fas fa-file-invoice-dollar',
            'Disbursed' => 'fas fa-hand-holding-usd',
            'Ready for Disbursement' => 'fas fa-hourglass-half',
            
            // Rolled back statuses
            'Processing[ROLLED BACK]' => 'fas fa-undo',
            'Submitted[ROLLED BACK]' => 'fas fa-undo',
            'Approved[ROLLED BACK]' => 'fas fa-undo',
            'Budget Allocated[ROLLED BACK]' => 'fas fa-undo',
           
        ];

        return $icons[$status] ?? 'fas fa-bell';
    }

    /**
     * Get notification icon color based on status
     */
    private static function getNotificationIconColor($status)
    {
        $colors = [
            'Processing' => 'info',
            'Submitted' => 'primary',
            'Submitted[Emergency]' => 'warning',
            'Approved' => 'success',
            'Rejected' => 'danger',
            'Budget Allocated' => 'success',
            'DV Submitted' => 'info',
            'Disbursed' => 'success',
            'Ready for Disbursement' => 'warning',
            
            // Rolled back statuses - use warning color for visibility
            'Processing[ROLLED BACK]' => 'warning',
            'Submitted[ROLLED BACK]' => 'warning',
            'Approved[ROLLED BACK]' => 'warning',
            'Budget Allocated[ROLLED BACK]' => 'warning',
           
        ];

        return $colors[$status] ?? 'secondary';
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = UserNotification::where('user_id', Auth::id())
            ->findOrFail($id);

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        UserNotification::where('user_id', Auth::id())
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * Get all notifications page
     */
    public function index()
    {
        $notifications = UserNotification::with([
            'statusLog.patient',
            'statusLog.user'
        ])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }
}