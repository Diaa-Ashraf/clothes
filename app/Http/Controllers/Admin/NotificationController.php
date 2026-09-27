<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display all notifications history page
     */
    public function index(): View
    {
        $notifications = AdminNotification::latest()->paginate(25);
        $unreadCount = AdminNotification::unread()->count();

        return view('admin.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * AJAX Feed: Returns unread count and latest notifications
     */
    public function feed(Request $request): JsonResponse
    {
        $limit = $request->integer('limit', 8);
        $lastId = $request->integer('last_id', 0);

        $unreadCount = AdminNotification::unread()->count();

        $notifications = AdminNotification::latest()
            ->take($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => $item->type,
                    'title' => $item->title,
                    'message' => $item->message,
                    'link' => $item->link ?? '#',
                    'icon' => $item->icon,
                    'is_read' => (bool) $item->is_read,
                    'time_ago' => $item->time_ago,
                    'created_at' => $item->created_at ? $item->created_at->toISOString() : null,
                ];
            });

        $latestId = AdminNotification::max('id') ?? 0;
        $hasNew = $lastId > 0 && $latestId > $lastId;

        // If there are new notifications since last poll, fetch the newly arrived items
        $newItems = [];
        if ($hasNew) {
            $newItems = AdminNotification::where('id', '>', $lastId)
                ->latest()
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'type' => $item->type,
                        'title' => $item->title,
                        'message' => $item->message,
                        'link' => $item->link ?? '#',
                        'time_ago' => $item->time_ago,
                    ];
                });
        }

        return response()->json([
            'status' => 'success',
            'unread_count' => $unreadCount,
            'latest_id' => $latestId,
            'has_new' => $hasNew,
            'new_items' => $newItems,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Mark single notification as read
     */
    public function markAsRead(int $id): JsonResponse
    {
        $notification = AdminNotification::find($id);
        if ($notification) {
            $notification->markAsRead();
        }

        $unreadCount = AdminNotification::unread()->count();

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديد الإشعار كمقروء',
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): JsonResponse
    {
        AdminNotification::unread()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديد جميع الإشعارات كمقروءة',
            'unread_count' => 0,
        ]);
    }

    /**
     * Delete a single notification
     */
    public function destroy(int $id): JsonResponse
    {
        AdminNotification::where('id', $id)->delete();
        $unreadCount = AdminNotification::unread()->count();

        return response()->json([
            'status' => 'success',
            'message' => 'تم حذف الإشعار بنجاح',
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Clear all notifications
     */
    public function clearAll(): JsonResponse
    {
        AdminNotification::truncate();

        return response()->json([
            'status' => 'success',
            'message' => 'تم مسح جميع الإشعارات بنجاح',
            'unread_count' => 0,
        ]);
    }
}
