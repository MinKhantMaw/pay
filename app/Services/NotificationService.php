<?php

namespace App\Services;

use App\Events\NotificationBroadcastEvent;
use App\Models\AdminUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class NotificationService
{
    public function notify(
        Model $recipient,
        string $title,
        string $message,
        ?string $webLink = null,
        array $extraData = []
    ): DatabaseNotification {
        $notification = $recipient->notifications()->create([
            'id' => (string) Str::uuid(),
            'type' => 'realtime',
            'data' => array_merge([
                'title' => $title,
                'message' => $message,
                'sourceable_id' => $extraData['sourceable_id'] ?? null,
                'sourceable_type' => $extraData['sourceable_type'] ?? null,
                'web_link' => $webLink,
                'deep_link' => $extraData['deep_link'] ?? null,
            ], $extraData),
        ]);

        broadcast(new NotificationBroadcastEvent($recipient, $notification));

        return $notification;
    }

    public function notifyAdmins(string $title, string $message, ?string $webLink = null, array $extraData = []): Collection
    {
        return AdminUser::query()
            ->get()
            ->map(fn (AdminUser $adminUser) => $this->notify($adminUser, $title, $message, $webLink, $extraData));
    }

    public function notifyUserCreated(Model $user): Collection
    {
        return $this->notifyAdmins(
            'New User Account',
            'New user account created: '.$user->name,
            route('user.user.index'),
            [
                'sourceable_id' => $user->getKey(),
                'sourceable_type' => $user::class,
                'deep_link' => [
                    'target' => 'admin_user_list',
                    'parameter' => ['user_id' => $user->getKey()],
                ],
            ]
        );
    }

    public function notifyContentCreated(Model $content, ?string $webLink = null): Collection
    {
        return $this->notifyAdmins(
            'Content Created',
            'New content created.',
            $webLink,
            [
                'sourceable_id' => $content->getKey(),
                'sourceable_type' => $content::class,
            ]
        );
    }

    public function notifyApproved(Model $record, Model|iterable $recipients, ?string $webLink = null): Collection
    {
        $recipientCollection = $recipients instanceof Model ? collect([$recipients]) : collect($recipients);

        return $recipientCollection->map(fn (Model $recipient) => $this->notify(
            $recipient,
            'Record Approved',
            'Your record has been approved.',
            $webLink,
            [
                'sourceable_id' => $record->getKey(),
                'sourceable_type' => $record::class,
            ]
        ));
    }
}
