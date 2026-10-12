<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total produk & Nilai inventory
        $totalProducts = Product::count();
        $totalInventoryValue = Product::sum(DB::raw('stock * price'));

        // 2. Produk stok rendah
        $lowStockCount = Product::whereColumn('stock', '<=', 'minimum_stock')->count();

        // 3. Total barang masuk (IN)
        $totalIn = StockMovement::where('type', 'IN')->sum('quantity');

        // 4. Total barang keluar (OUT)
        $totalOut = StockMovement::where('type', 'OUT')->sum('quantity');

        // 5. Total supplier & kategori
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();

        // 6. Produk dengan stok terendah untuk widget alert
        $lowStockProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock', 'asc')
            ->orderBy('name', 'asc')
            ->limit(5)
            ->get();

        // 7. Riwayat transaksi mutasi terkini
        $recentMovements = StockMovement::with(['product', 'user.profile'])
            ->latest()
            ->limit(5)
            ->get();

        $stats = [
            'total_products' => $totalProducts,
            'total_inventory_value' => $totalInventoryValue,
            'low_stock_count' => $lowStockCount,
            'total_in' => $totalIn,
            'total_out' => $totalOut,
            'total_categories' => $totalCategories,
            'total_suppliers' => $totalSuppliers,
            'low_stock_products' => $lowStockProducts,
            'recent_movements' => $recentMovements,
        ];

        return view('dashboard.index', compact('stats'));
    }
}
