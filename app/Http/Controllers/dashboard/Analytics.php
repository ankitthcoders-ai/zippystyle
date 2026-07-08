<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use App\Support\RoleAccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Analytics extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isAdmin = RoleAccess::isAdmin();

        $productQuery = RoleAccess::products();
        $shopQuery = RoleAccess::shops();
        $orderQuery = RoleAccess::merchantOrders();
        $salesQuery = RoleAccess::merchantSales();

        if ($isAdmin) {
            $totalRevenue = (float) $orderQuery->sum('total_paid_price');
            $totalOrders = (int) $orderQuery->count();
        } else {
            $totalRevenue = (float) (clone $salesQuery)->sum('paid_price');
            $totalOrders = (int) (clone $salesQuery)->distinct('purchase_id')->count('purchase_id');
        }

        $todayRevenue = $isAdmin
            ? (float) (clone $orderQuery)->whereDate('created_at', today())->sum('total_paid_price')
            : (float) (clone $salesQuery)->whereHas('purchase', fn ($q) => $q->whereDate('created_at', today()))->sum('paid_price');

        $todayOrders = $isAdmin
            ? (int) (clone $orderQuery)->whereDate('created_at', today())->count()
            : (int) (clone $salesQuery)->whereHas('purchase', fn ($q) => $q->whereDate('created_at', today()))->distinct('purchase_id')->count('purchase_id');

        $monthRevenue = $isAdmin
            ? (float) (clone $orderQuery)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total_paid_price')
            : (float) (clone $salesQuery)->whereHas('purchase', fn ($q) => $q->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year))->sum('paid_price');

        $lastMonthRevenue = $isAdmin
            ? (float) (clone $orderQuery)->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year)->sum('total_paid_price')
            : (float) (clone $salesQuery)->whereHas('purchase', fn ($q) => $q->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year))->sum('paid_price');

        $monthlyRevenue = [];
        $monthlyOrders = [];
        $monthLabels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthLabels[] = $date->format('M');

            if ($isAdmin) {
                $monthQuery = (clone $orderQuery)
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year);
                $monthlyRevenue[] = (float) $monthQuery->sum('total_paid_price');
                $monthlyOrders[] = (int) (clone $monthQuery)->count();
            } else {
                $monthSales = (clone $salesQuery)->whereHas('purchase', fn ($q) => $q->whereMonth('created_at', $date->month)->whereYear('created_at', $date->year));
                $monthlyRevenue[] = (float) (clone $monthSales)->sum('paid_price');
                $monthlyOrders[] = (int) (clone $monthSales)->distinct('purchase_id')->count('purchase_id');
            }
        }

        $weeklyRevenue = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $weeklyRevenue[] = $isAdmin
                ? (float) (clone $orderQuery)->whereDate('created_at', $date->toDateString())->sum('total_paid_price')
                : (float) (clone $salesQuery)->whereHas('purchase', fn ($q) => $q->whereDate('created_at', $date->toDateString()))->sum('paid_price');
        }

        $categoryStats = (clone $salesQuery)
            ->join('products', 'purchased_products.product_id', '=', 'products.id')
            ->join('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
            ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->select('categories.category_name', DB::raw('COUNT(purchased_products.id) as total'))
            ->groupBy('categories.id', 'categories.category_name')
            ->orderByDesc('total')
            ->limit(4)
            ->get();

        $recentPurchases = (clone $orderQuery)
            ->with(['user', 'paymentMethod'])
            ->latest()
            ->limit(6)
            ->get();

        $stats = [
            'user_name' => $user->name,
            'role_label' => $isAdmin ? 'Admin' : 'Merchant',
            'total_products' => (clone $productQuery)->count(),
            'total_shops' => (clone $shopQuery)->count(),
            'total_categories' => $isAdmin ? Category::count() : Category::count(),
            'total_users' => $isAdmin ? User::count() : 1,
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'today_revenue' => $todayRevenue,
            'today_orders' => $todayOrders,
            'month_revenue' => $monthRevenue,
            'revenue_growth' => $this->growthPercent($monthRevenue, $lastMonthRevenue),
            'orders_growth' => $this->growthPercent(
                $isAdmin
                    ? (clone $orderQuery)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count()
                    : (clone $salesQuery)->whereHas('purchase', fn ($q) => $q->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year))->distinct('purchase_id')->count('purchase_id'),
                $isAdmin
                    ? (clone $orderQuery)->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year)->count()
                    : (clone $salesQuery)->whereHas('purchase', fn ($q) => $q->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year))->distinct('purchase_id')->count('purchase_id')
            ),
            'items_sold' => (clone $salesQuery)->count(),
        ];

        $chartData = [
            'monthLabels' => $monthLabels,
            'monthlyRevenue' => $monthlyRevenue,
            'monthlyOrders' => $monthlyOrders,
            'weeklyRevenue' => $weeklyRevenue,
            'weeklyLabels' => ['M', 'T', 'W', 'T', 'F', 'S', 'S'],
            'categoryLabels' => $categoryStats->pluck('category_name')->values()->all(),
            'categoryTotals' => $categoryStats->pluck('total')->map(fn ($v) => (int) $v)->values()->all(),
            'growthPercent' => min(max($stats['revenue_growth'], 0), 100),
            'profileReportData' => array_slice($monthlyRevenue, -6),
            'incomeChartData' => $monthlyRevenue,
        ];

        return view('content.dashboard.dashboards-analytics', compact('stats', 'chartData', 'categoryStats', 'recentPurchases'));
    }

    private function growthPercent(float $current, float $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }
}
