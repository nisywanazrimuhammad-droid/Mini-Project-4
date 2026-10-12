<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function inventory(Request $request)
    {
        $categoryId = $request->input('category_id');
        $categories = Category::orderBy('name')->get();

        $query = Product::query()
            ->select([
                'products.id',
                'products.sku',
                'products.name',
                'products.price',
                'products.stock',
                'products.minimum_stock',
                'categories.name as category_name',
                DB::raw("COALESCE(SUM(CASE WHEN stock_movements.type = 'IN' THEN stock_movements.quantity ELSE 0 END), 0) as total_in"),
                DB::raw("COALESCE(SUM(CASE WHEN stock_movements.type = 'OUT' THEN stock_movements.quantity ELSE 0 END), 0) as total_out"),
                DB::raw('(products.stock * products.price) as inventory_value'),
            ])
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->leftJoin('stock_movements', 'stock_movements.product_id', '=', 'products.id')
            ->groupBy([
                'products.id',
                'products.sku',
                'products.name',
                'products.price',
                'products.stock',
                'products.minimum_stock',
                'categories.name',
            ])
            ->orderBy('categories.name')
            ->orderBy('products.name');

        if (!empty($categoryId)) {
            $query->where('products.category_id', $categoryId);
        }

        $report = $query->get();

        $summary = [
            'total_val' => $report->sum('inventory_value'),
            'total_stock' => $report->sum('stock'),
            'total_in' => $report->sum('total_in'),
            'total_out' => $report->sum('total_out'),
        ];

        return view('reports.inventory', compact('report', 'categories', 'categoryId', 'summary'));
    }
}
