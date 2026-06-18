<div class="app-sidebar sidebar-shadow">
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
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <li class="app-sidebar__heading">Dashboards</li>
                <li>
                    <a href="{{ route('admin.home') }}" class="@yield('dashboard')">
                        <i class="metismenu-icon pe-7s-rocket"></i>
                        Dashboards
                    </a>
                </li>
                @can('role.manage')
                    <li>
                        <a href="{{ route('admin.admin-user.index') }}" class="@yield('admin-user-index')">
                            <i class="metismenu-icon pe-7s-users"></i>Admin Users
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.roles.index') }}" class="@yield('role-index')">
                            <i class="metismenu-icon pe-7s-key"></i>Roles
                        </a>
                    </li>
                @endcan
                @can('role.view')
                    <li>
                        <a href="{{ route('admin.permissions.index') }}" class="@yield('permission-index')">
                            <i class="metismenu-icon pe-7s-lock"></i>Permissions
                        </a>
                    </li>
                @endcan
                @can('user.view')
                    <li>
                        <a href="{{ route('user.user.index') }}" class="@yield('user-index')">
                            <i class="metismenu-icon pe-7s-users"></i> Users
                        </a>
                    </li>
                @endcan
                @can('wallet.view')
                    <li>
                        <a href="{{ route('wallet.index') }}" class="@yield('wallet-index')">
                            <i class="metismenu-icon pe-7s-wallet"></i> Wallet Management
                        </a>
                    </li>
                @endcan
                @can('approval.view')
                    <li>
                        <a href="{{ route('admin.approvals.index') }}" class="@yield('approval-index')">
                            <i class="metismenu-icon pe-7s-check"></i> Approvals
                        </a>
                    </li>
                @endcan
                @can('audit.view')
                    <li>
                        <a href="{{ route('admin.audit-logs.index') }}" class="@yield('audit-log-index')">
                            <i class="metismenu-icon pe-7s-note2"></i> Audit Logs
                        </a>
                    </li>
                @endcan
            </ul>
        </div>
    </div>
</div>
