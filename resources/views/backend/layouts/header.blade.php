<div class="app-header header-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>
    <div class="app-header__content">
        <div class="app-header-right">
            <div class="header-btn-lg pr-0 mr-3" id="realtime-notifications"
                data-channel-id="{{ $notification_channel_id }}"
                data-index-url="{{ route('admin.notifications.index') }}"
                data-mark-url-template="{{ route('admin.notifications.mark-as-read', ['notification' => '__ID__']) }}">
                <div class="btn-group">
                    <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                        class="btn btn-link p-0 position-relative">
                        <i class="fa fa-bell fa-lg"></i>
                        <span id="notificationUnreadCount"
                            class="badge badge-danger position-absolute"
                            style="top: -10px; right: -12px; {{ $unread_noti_count > 0 ? '' : 'display: none;' }}">
                            {{ $unread_noti_count }}
                        </span>
                    </button>
                    <div tabindex="-1" role="menu" aria-hidden="true"
                        class="dropdown-menu dropdown-menu-right dropdown-menu-lg p-0">
                        <div class="dropdown-menu-header">
                            <div class="dropdown-menu-header-inner bg-info">
                                <div class="menu-header-content text-left">
                                    <div class="widget-heading text-white">Notifications</div>
                                </div>
                            </div>
                        </div>
                        <div id="notificationDropdownList" class="list-group list-group-flush"
                            style="max-height: 360px; overflow-y: auto;">
                            @forelse ($header_notifications as $notification)
                                <a href="{{ $notification->data['web_link'] ?? '#' }}"
                                    class="list-group-item list-group-item-action notification-item {{ is_null($notification->read_at) ? 'font-weight-bold' : '' }}"
                                    data-notification-id="{{ $notification->id }}">
                                    <div class="small text-muted">
                                        {{ $notification->created_at->format('Y-m-d h:i A') }}
                                    </div>
                                    <div>{{ $notification->data['title'] ?? 'Notification' }}</div>
                                    <div class="small text-muted">
                                        {{ Illuminate\Support\Str::limit($notification->data['message'] ?? '', 70) }}
                                    </div>
                                </a>
                            @empty
                                <div class="list-group-item text-muted small" id="notificationEmptyState">
                                    No notifications
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-btn-lg pr-0">
                <div class="widget-content p-0">
                    <div class="widget-content-wrapper">
                        <div class="widget-content-left">
                            <div class="btn-group">
                                <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                    <img width="42" class="rounded-circle"
                                        src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}" alt="">
                                    <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                </a>
                                <div tabindex="-1" role="menu" aria-hidden="true"
                                    class="rm-pointers dropdown-menu-lg dropdown-menu dropdown-menu-right">
                                    <div class="dropdown-menu-header">
                                        <div class="dropdown-menu-header-inner bg-info">
                                            <div class="menu-header-image opacity-2" style="">
                                            </div>
                                            <div class="menu-header-content text-left">
                                                <div class="widget-content p-0">
                                                    <div class="widget-content-wrapper">
                                                        <div class="widget-content-left mr-3">
                                                            <img width="42" class="rounded-circle"
                                                                src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}"
                                                                alt="">
                                                        </div>
                                                        <div class="widget-content-left">
                                                            <div class="widget-heading">
                                                                {{ Auth::user()->name }}
                                                            </div>
                                                            <div class="widget-subheading opacity-8">
                                                                {{ Auth::user()->email }}
                                                            </div>
                                                        </div>
                                                        <div class="widget-content-right ">
                                                            <a class="dropdown-item" href="{{ route('admin.logout') }}"
                                                                onclick="event.preventDefault();
                                                                             document.getElementById('logout-form').submit();">
                                                                {{ __('Logout') }}
                                                            </a>

                                                            <form id="logout-form" action="{{ route('admin.logout') }}"
                                                                method="POST">
                                                                @csrf
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
