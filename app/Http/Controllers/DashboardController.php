<?php

namespace App\Http\Controllers;

use App\Models\Fruit;
use App\Models\Stock;
use App\Models\Transaction;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Threshold (in any unit) below which a fruit is considered "low stock".
     */
    private const LOW_STOCK_THRESHOLD = 10;

    public function index(): View
    {
        // ── Summary cards ────────────────────────────────────────────────────
        $totalFruits = Fruit::count();

        $totalTransactionsToday = Transaction::whereDate('transaction_date', today())->count();

        $revenueToday = Transaction::whereDate('transaction_date', today())
            ->where('status', 'paid')
            ->sum('total_amount');

        // ── Low-stock fruits ─────────────────────────────────────────────────
        // Calculate net stock per fruit: SUM(in) + SUM(adjustment) - SUM(out)
        $stockSummary = Stock::select(
                'fruit_id',
                DB::raw("
                    SUM(CASE
                        WHEN type = 'in'         THEN quantity
                        WHEN type = 'adjustment' THEN quantity
                        WHEN type = 'out'        THEN -quantity
                        ELSE 0
                    END) as net_quantity
                ")
            )
            ->groupBy('fruit_id')
            ->get()
            ->keyBy('fruit_id');

        // Attach net quantity to every fruit
        $fruits = Fruit::all()->map(function ($fruit) use ($stockSummary) {
            $fruit->net_quantity = isset($stockSummary[$fruit->id])
                ? (float) $stockSummary[$fruit->id]->net_quantity
                : 0.0;
            return $fruit;
        });

        $lowStockFruits = $fruits
            ->filter(fn($f) => $f->net_quantity < self::LOW_STOCK_THRESHOLD)
            ->sortBy('net_quantity')
            ->values();

        $totalLowStock = $lowStockFruits->count();

        // ── Expiry / Rotten Fruits Calculation ──────────────────────────────
        $inStocksWithExpiry = Stock::with(['fruit', 'supplier'])
            ->where('type', 'in')
            ->whereNotNull('expired_at')
            ->orderBy('expired_at', 'asc')
            ->get();

        $expiredStocks = $inStocksWithExpiry->filter(fn($s) => $s->expiry_status === 'expired')->values();
        $nearExpiryStocks = $inStocksWithExpiry->filter(fn($s) => $s->expiry_status === 'near_expiry')->values();

        $totalExpiredStocksCount = $expiredStocks->count();
        $totalNearExpiryStocksCount = $nearExpiryStocks->count();
        $totalRottenWarningCount = $totalExpiredStocksCount + $totalNearExpiryStocksCount;

        $rottenWarningStocksList = $expiredStocks->concat($nearExpiryStocks)->take(10);

        // ── Recent transactions ───────────────────────────────────────────────
        $recentTransactions = Transaction::with('user')
            ->latest('transaction_date')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalFruits',
            'totalTransactionsToday',
            'revenueToday',
            'lowStockFruits',
            'totalLowStock',
            'totalExpiredStocksCount',
            'totalNearExpiryStocksCount',
            'totalRottenWarningCount',
            'rottenWarningStocksList',
            'recentTransactions',
        ));
    }
}
