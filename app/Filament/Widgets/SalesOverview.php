<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\SalesItem; // ✅ Corrected
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget;
use Illuminate\Support\Carbon;

class SalesOverview extends StatsOverviewWidget
{
    protected function getCards(): array
    {
        $today = Carbon::today();
        $month = Carbon::now()->month;

        $todaySales = SalesItem::whereDate('created_at', $today)
            ->get()
            ->sum(fn ($item) => $item->price * $item->quantity);

        $monthlySales = SalesItem::whereMonth('created_at', $month)
            ->get()
            ->sum(fn ($item) => $item->price * $item->quantity);

        $totalSales = SalesItem::all()
            ->sum(fn ($item) => $item->price * $item->quantity);

        $topProduct = Product::withSum('salesItems as sold', 'quantity')
            ->orderByDesc('sold')
            ->first();

        return [
            Card::make("Penjualan Hari Ini", 'Rp' . number_format($todaySales, 0, ',', '.'))
                ->description("Total penjualan hari ini")
                ->color('success'),

            Card::make("Penjualan Bulan Ini", 'Rp' . number_format($monthlySales, 0, ',', '.'))
                ->description("Total penjualan bulan berjalan")
                ->color('info'),

            Card::make("Total Keseluruhan", 'Rp' . number_format($totalSales, 0, ',', '.'))
                ->description("Akumulasi seluruh penjualan")
                ->color('warning'),

            Card::make("Top Produk", $topProduct?->name ?? '-')
                ->description($topProduct ? "Terjual {$topProduct->sold} pcs" : "Belum ada penjualan")
                ->color('primary'),
        ];
    }
}
