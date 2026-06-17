<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        view()->composer('*', function ($view) {
            $unread_noti_count = 0;
            $header_notifications = collect();
            $notification_channel_id = null;

            if (auth()->guard('admin_user')->check()) {
                $notifiable = auth()->guard('admin_user')->user();
                $unread_noti_count = $notifiable->unreadNotifications()->count();
                $header_notifications = $notifiable->notifications()->latest()->limit(5)->get();
                $notification_channel_id = $notifiable->id;
            } elseif (auth()->guard('web')->check()) {
                $notifiable = auth()->guard('web')->user();
                $unread_noti_count = $notifiable->unreadNotifications()->count();
                $header_notifications = $notifiable->notifications()->latest()->limit(5)->get();
                $notification_channel_id = $notifiable->id;
            }

            $view->with('unread_noti_count', $unread_noti_count);
            $view->with('header_notifications', $header_notifications);
            $view->with('notification_channel_id', $notification_channel_id);
        });
    }
}
