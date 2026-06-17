<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $notifiable = $this->notifiable();

        $notifications = $notifiable->notifications()
            ->latest()
            ->limit((int) $request->integer('limit', 10))
            ->get()
            ->map(fn ($notification) => $this->notificationPayload($notification));

        return response()->json([
            'unread_count' => $notifiable->unreadNotifications()->count(),
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(string $notification): JsonResponse
    {
        $notifiable = $this->notifiable();
        $notificationModel = $notifiable->notifications()->where('id', $notification)->firstOrFail();

        if (is_null($notificationModel->read_at)) {
            $notificationModel->markAsRead();
        }

        return response()->json([
            'unread_count' => $notifiable->unreadNotifications()->count(),
            'notification' => $this->notificationPayload($notificationModel->refresh()),
            'redirect_url' => $notificationModel->data['web_link'] ?? null,
        ]);
    }

    private function notifiable()
    {
        if (Auth::guard('admin_user')->check()) {
            return Auth::guard('admin_user')->user();
        }

        return Auth::guard('web')->user();
    }

    private function notificationPayload($notification): array
    {
        return [
            'id' => $notification->id,
            'title' => $notification->data['title'] ?? 'Notification',
            'message' => $notification->data['message'] ?? '',
            'url' => $notification->data['web_link'] ?? null,
            'read_at' => $notification->read_at,
            'created_at' => $notification->created_at?->toDateTimeString(),
        ];
    }
}
