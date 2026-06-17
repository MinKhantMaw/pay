<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Queue\SerializesModels;

class NotificationBroadcastEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $recipientId;

    public array $notification;

    public bool $afterCommit = true;

    public function __construct(Model $recipient, DatabaseNotification $notification)
    {
        $this->recipientId = (int) $recipient->getKey();
        $this->notification = [
            'id' => $notification->id,
            'title' => $notification->data['title'] ?? 'Notification',
            'message' => $notification->data['message'] ?? '',
            'url' => $notification->data['web_link'] ?? null,
            'read_at' => $notification->read_at,
            'created_at' => $notification->created_at?->toDateTimeString(),
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('private-user.'.$this->recipientId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'NotificationBroadcastEvent';
    }

    public function broadcastWith(): array
    {
        return [
            'notification' => $this->notification,
        ];
    }
}
