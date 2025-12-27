<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * جلب الإشعارات للمستخدم الحالي
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = UserNotification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        // التصفية حسب الحالة
        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->unread();
            } elseif ($request->status === 'read') {
                $query->read();
            }
        }

        $notifications = $query->limit(50)->get()->map(function ($notification) {
            return [
                'id' => $notification->id,
                'type' => $notification->type,
                'title' => $notification->title,
                'message' => $notification->message,
                'icon' => $notification->icon,
                'color' => $notification->color,
                'link' => $notification->link,
                'is_read' => $notification->is_read,
                'created_at' => $notification->created_at->toISOString(),
            ];
        });

        // عدد الإشعارات غير المقروءة
        $unreadCount = UserNotification::where('user_id', $user->id)
            ->unread()
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * تحديد إشعار كمقروء
     */
    public function markAsRead(UserNotification $notification)
    {
        $user = Auth::user();

        // التحقق من أن الإشعار يخص هذا المستخدم
        if ($notification->user_id !== $user->id) {
            return response()->json(['error' => 'غير مصرح'], 403);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * تحديد جميع الإشعارات كمقروءة
     */
    public function markAllAsRead()
    {
        $user = Auth::user();

        UserNotification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * حذف إشعار
     */
    public function destroy(UserNotification $notification)
    {
        $user = Auth::user();

        // التحقق من أن الإشعار يخص هذا المستخدم
        if ($notification->user_id !== $user->id) {
            return response()->json(['error' => 'غير مصرح'], 403);
        }

        $notification->delete();

        return response()->json(['success' => true]);
    }
}
