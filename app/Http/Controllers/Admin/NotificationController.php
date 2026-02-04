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
     * Get notifications with pagination/load more support
     */
    public function getNotifications(Request $request)
    {
        $perPage = 10; // Load 10 notifications at a time
        $page = $request->get('page', 1);
        $departmentFilter = $request->get('department', '');
        $loadMore = $request->boolean('load_more', false);
        
        $query = UserNotification::with([
            'statusLog.patient',
            'statusLog.user'
        ])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');
        
        // Apply department filter if provided
        if ($departmentFilter) {
            $query->whereHas('statusLog', function ($q) use ($departmentFilter) {
                $q->where(function ($subQuery) use ($departmentFilter) {
                    $departmentMapping = self::getDepartmentMapping();
                    $statuses = array_keys(array_filter($departmentMapping, function ($dept) use ($departmentFilter) {
                        return $dept === $departmentFilter;
                    }));
                    
                    if (!empty($statuses)) {
                        $subQuery->whereIn('status', $statuses);
                    }
                });
            });
        }
        
        // Calculate total and loaded counts
        $totalNotifications = $query->count();
        $hasMore = false;
        
        if ($loadMore) {
            // For load more, get next page
            $notifications = $query->paginate($perPage, ['*'], 'page', $page);
            $hasMore = $notifications->hasMorePages();
        } else {
            // Initial load, get first page
            $notifications = $query->paginate($perPage);
            $hasMore = $notifications->hasMorePages();
        }
        
        // Transform notifications
        $transformedNotifications = $notifications->map(function ($notification) {
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
        })->filter()->values();
        
        return response()->json([
            'notifications' => $transformedNotifications,
            'total' => $totalNotifications,
            'has_more' => $hasMore,
            'next_page' => $hasMore ? ($page + 1) : null,
            'current_page' => $page
        ]);
    }
    
    /**
     * Get department mapping for filtering
     */
    private static function getDepartmentMapping()
    {
        return [
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
            'Disbursed' => 'Treasury Office',
            'Ready for Disbursement' => 'Treasury Office',
        ];
    }

    /**
     * Get department by status
     */
    private static function getDepartmentByStatus($status)
    {
        $mapping = self::getDepartmentMapping();
        return $mapping[$status] ?? 'General';
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
            
            // Rolled back statuses
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
     * Get all notifications page (for dedicated notifications page)
     */
    public function index()
    {
        $notifications = UserNotification::with([
            'statusLog.patient',
            'statusLog.user'
        ])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(50); // Use pagination for the dedicated page

        return view('admin.notifications.index', compact('notifications'));
    }
}