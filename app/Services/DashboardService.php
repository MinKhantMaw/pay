<?php

namespace App\Services;

use App\Models\AdminUser;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function data(): array
    {
        $adminUser = Auth::guard('admin_user')->user();
        $monthStarts = collect(range(11, 0))->map(fn ($monthsAgo) => Carbon::now()->subMonths($monthsAgo)->startOfMonth());

        return [
            'totalAdminUsers' => AdminUser::count(),
            'totalUsers' => User::count(),
            'totalWallets' => Wallet::count(),
            'totalWalletBalance' => Wallet::sum('amount'),
            'todayTransactions' => Transaction::whereDate('created_at', Carbon::today())->count(),
            'pendingRequests' => 0,
            'unreadNotifications' => $adminUser ? $adminUser->unreadNotifications()->count() : 0,
            'latestUsers' => User::latest()->limit(5)->get(),
            'latestTransactions' => Transaction::with(['user', 'source'])->latest()->limit(5)->get(),
            'latestUnreadNotifications' => $adminUser
                ? $adminUser->unreadNotifications()->latest()->limit(5)->get()
                : collect(),
            'dashboardChartLabels' => $monthStarts->map(fn (Carbon $month) => $month->format('M Y'))->values(),
            'monthlyUserRegistrations' => $this->monthlyCounts(User::query(), $monthStarts),
            'monthlyWalletTransactions' => $this->monthlyCounts(Transaction::query(), $monthStarts),
        ];
    }

    private function monthlyCounts(Builder $query, Collection $monthStarts): Collection
    {
        $startDate = $monthStarts->first()->copy()->startOfMonth();
        $driver = DB::connection()->getDriverName();
        $monthExpression = $driver === 'sqlite'
            ? "strftime('%Y-%m', created_at)"
            : "DATE_FORMAT(created_at, '%Y-%m')";

        $counts = (clone $query)
            ->selectRaw($monthExpression.' as month_key, COUNT(*) as aggregate_count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('month_key')
            ->pluck('aggregate_count', 'month_key');

        return $monthStarts
            ->map(fn (Carbon $month) => (int) ($counts[$month->format('Y-m')] ?? 0))
            ->values();
    }
}
