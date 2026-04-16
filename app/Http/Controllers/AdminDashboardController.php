<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', $this->statsFullData());
    }

    /** @return array<string, mixed> */
    private function statsSummaryData(): array
    {
        $unpaidOrdersCount = Order::query()
            ->whereRaw('UPPER(payment_status) = ?', ['UNPAID'])
            ->count();

        $paidOrdersCount = Order::query()
            ->whereRaw('UPPER(payment_status) = ?', ['PAID'])
            ->count();

        $totalProductsCount = Product::query()->count();
        $outOfStockProductsCount = Product::query()
            ->where('stock', 0)
            ->count();
        $inStockProductsCount = Product::query()
            ->where('stock', '>', 0)
            ->count();

        return [
            'unpaidOrdersCount' => $unpaidOrdersCount,
            'paidOrdersCount' => $paidOrdersCount,
            'totalProductsCount' => $totalProductsCount,
            'inStockProductsCount' => $inStockProductsCount,
            'outOfStockProductsCount' => $outOfStockProductsCount,
        ];
    }

    /** @return array<string, mixed> */
    private function statsFullData(): array
    {
        $data = $this->statsSummaryData();

        $orders = Order::query()
            ->with([
                'user:id,name,email',
                // Orders can have multiple shipping records; in the view we show the latest one.
                'shipping:id,order_id,status,created_at',
            ])
            ->latest()
            ->limit(10)
            ->get(['id', 'order_number', 'user_id', 'status', 'payment_status', 'created_at']);

        $outOfStockProducts = Product::query()
            ->with([
                'category:id,name',
                'images',
            ])
            ->where('stock', 0)
            ->latest()
            ->limit(10)
            ->get(['id', 'name', 'slug', 'category_id', 'subcategory_id', 'price', 'stock', 'is_active']);

        $data['orders'] = $orders;
        $data['outOfStockProducts'] = $outOfStockProducts;

        return $data;
    }
}

