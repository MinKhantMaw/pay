<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
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
            if (auth()->guard('web')->check()) {
                $unread_noti_count = auth()->guard('web')->user()->unreadNotifications()->count();
            };
            $view->with('unread_noti_count', $unread_noti_count);
        });
    }
}
