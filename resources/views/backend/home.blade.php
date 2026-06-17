@extends('backend.layouts.app')
@section('dashboard', 'mm-active')
@section('title', 'Admin Dashboard')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-display2 icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div>Admin Dashboard
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <x-dashboard-stat-card title="Total Admin Users" subtitle="Active admin accounts"
            :value="number_format($totalAdminUsers)" card-class="bg-night-fade" text-class="text-white"
            number-class="text-white" />
        <x-dashboard-stat-card title="Total Users" subtitle="Registered customers"
            :value="number_format($totalUsers)" card-class="bg-arielle-smile" text-class="text-white"
            number-class="text-white" />
        <x-dashboard-stat-card title="Total Wallets" subtitle="Wallet accounts"
            :value="number_format($totalWallets)" card-class="bg-grow-early" text-class="text-white"
            number-class="text-white" />
        <x-dashboard-stat-card title="Wallet Balance" subtitle="Total MMK balance"
            :value="number_format($totalWalletBalance, 2)" card-class="bg-strong-bliss" text-class="text-white"
            number-class="text-white" />
        <x-dashboard-stat-card title="Today Transactions" subtitle="Created today"
            :value="number_format($todayTransactions)" column-class="col-md-6 col-xl-4" number-class="text-success" />
        <x-dashboard-stat-card title="Pending Requests" subtitle="Awaiting action"
            :value="number_format($pendingRequests)" column-class="col-md-6 col-xl-4" number-class="text-warning" />
        <x-dashboard-stat-card title="Unread Notifications" subtitle="For current admin"
            :value="number_format($unreadNotifications)" column-class="col-md-6 col-xl-4" number-class="text-danger" />
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="main-card mb-3 card">
                <div class="card-header">Monthly User Registration Chart</div>
                <div class="card-body dashboard-chart-card">
                    <canvas id="monthlyUserChart" height="140"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="main-card mb-3 card">
                <div class="card-header">Monthly Wallet Transaction Chart</div>
                <div class="card-body dashboard-chart-card">
                    <canvas id="monthlyTransactionChart" height="140"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="main-card mb-3 card">
                <div class="card-header">Latest 5 Users</div>
                <div class="table-responsive">
                    <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($latestUsers as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ optional($user->created_at)->format('Y-m-d') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="main-card mb-3 card">
                <div class="card-header">Latest 5 Wallet Transactions</div>
                <div class="table-responsive">
                    <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                        <thead>
                            <tr>
                                <th>TRX ID</th>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($latestTransactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->trx_id }}</td>
                                    <td>{{ optional($transaction->user)->name ?? 'Unknown' }}</td>
                                    <td>{{ number_format($transaction->amount, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $transaction->type == 1 ? 'badge-success' : 'badge-danger' }}">
                                            {{ $transaction->type == 1 ? 'Income' : 'Expense' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No transactions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="main-card mb-3 card">
                <div class="card-header">Latest 5 Unread Notifications</div>
                <div class="list-group list-group-flush">
                    @forelse ($latestUnreadNotifications as $notification)
                        <a href="{{ $notification->data['web_link'] ?? '#' }}" class="list-group-item list-group-item-action">
                            <div class="font-weight-bold">{{ $notification->data['title'] ?? 'Notification' }}</div>
                            <div class="text-muted small">
                                {{ Illuminate\Support\Str::limit($notification->data['message'] ?? '', 90) }}
                            </div>
                            <div class="text-muted small">{{ optional($notification->created_at)->format('Y-m-d h:i A') }}</div>
                        </a>
                    @empty
                        <div class="list-group-item text-center text-muted">No unread notifications.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const dashboardChartLabels = @json($dashboardChartLabels);
        const monthlyUserRegistrations = @json($monthlyUserRegistrations);
        const monthlyWalletTransactions = @json($monthlyWalletTransactions);

        new Chart(document.getElementById('monthlyUserChart'), {
            type: 'line',
            data: {
                labels: dashboardChartLabels,
                datasets: [{
                    label: 'Users',
                    data: monthlyUserRegistrations,
                    borderColor: '#3f6ad8',
                    backgroundColor: 'rgba(63, 106, 216, 0.12)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        new Chart(document.getElementById('monthlyTransactionChart'), {
            type: 'bar',
            data: {
                labels: dashboardChartLabels,
                datasets: [{
                    label: 'Transactions',
                    data: monthlyWalletTransactions,
                    backgroundColor: '#3ac47d'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endsection
