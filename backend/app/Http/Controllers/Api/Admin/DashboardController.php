<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $paidTransactions = Transaction::where('status', 'paid');

        $revenue = (clone $paidTransactions)->sum('grand_total');
        $transactionCount = (clone $paidTransactions)->count();
        $itemsSold = TransactionItem::sum('qty');

        $avgTransaction = $transactionCount > 0
            ? round($revenue / $transactionCount)
            : 0;

        // Grafik 7 hari terakhir
        $salesTrend = collect(range(6, 0))
            ->map(function ($day) {
                $date = Carbon::today()->subDays($day);

                return [
                    'label' => $date->translatedFormat('D'),
                    'value' => Transaction::where('status', 'paid')
                        ->whereDate('transaction_date', $date)
                        ->sum('grand_total'),
                ];
            })
            ->values();

        // Breakdown status
        $totalTransactions = Transaction::count();

        $paidCount = Transaction::where('status', 'paid')->count();
        $cancelledCount = Transaction::where('status', 'cancelled')->count();

        $statusBreakdown = [
            [
                'status' => 'paid',
                'label' => 'Lunas',
                'count' => $paidCount,
                'percent' => $totalTransactions
                    ? round(($paidCount / $totalTransactions) * 100)
                    : 0,
            ],
            [
                'status' => 'cancelled',
                'label' => 'Dibatalkan',
                'count' => $cancelledCount,
                'percent' => $totalTransactions
                    ? round(($cancelledCount / $totalTransactions) * 100)
                    : 0,
            ],
        ];

        // Produk stok menipis
        $lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')
            ->select('id', 'name', 'stock', 'min_stock')
            ->limit(5)
            ->get();

        // Transaksi terbaru
        $recentTransactions = Transaction::with('cashier')
            ->latest('transaction_date')
            ->limit(5)
            ->get();

        return response()->json([
            'data' => [
                'summary' => [
                    'revenue' => $revenue,
                    'transactionCount' => $transactionCount,
                    'itemsSold' => $itemsSold,
                    'avgTransaction' => $avgTransaction,
                ],

                'counts' => [
                    'users' => User::count(),
                    'products' => Product::count(),
                    'transactions' => $totalTransactions,
                ],

                'salesTrend' => $salesTrend,

                'statusBreakdown' => $statusBreakdown,

                'lowStockProducts' => $lowStockProducts,

                'recentTransactions' => $recentTransactions,
            ],
        ]);
    }
}