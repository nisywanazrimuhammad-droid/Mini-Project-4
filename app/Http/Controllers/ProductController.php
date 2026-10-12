<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        $lowStock = $request->boolean('low_stock');
        $sort = $request->input('sort', 'name');
        $dir = strtolower($request->input('dir', 'asc')) === 'desc' ? 'desc' : 'asc';

        // Allowlist sorting
        $allowedSorts = ['name', 'sku', 'price', 'stock', 'created_at'];
        $sortColumn = in_array($sort, $allowedSorts, true) ? $sort : 'name';

        $query = Product::with(['category', 'suppliers'])
            ->withCount('suppliers');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        if ($lowStock) {
            $query->whereColumn('stock', '<=', 'minimum_stock');
        }

        $products = $query->orderBy($sortColumn, $dir)->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        $filters = [
            'search' => $search,
            'category_id' => $categoryId,
            'low_stock' => $lowStock,
            'sort' => $sortColumn,
            'dir' => $dir,
        ];

        return view('products.index', compact('products', 'categories', 'filters'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'supplier_ids' => ['nullable', 'array'],
            'supplier_ids.*' => ['exists:suppliers,id'],
            'supplier_prices' => ['nullable', 'array'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            $stock = $validated['stock'] ?? 0;

            $product = Product::create([
                'category_id' => $validated['category_id'],
                'sku' => strtoupper($validated['sku']),
                'name' => $validated['name'],
                'price' => $validated['price'],
                'stock' => $stock,
                'minimum_stock' => $validated['minimum_stock'],
            ]);

            // Many-to-many suppliers
            if (!empty($validated['supplier_ids'])) {
                $syncData = [];
                foreach ($validated['supplier_ids'] as $sId) {
                    $price = $validated['supplier_prices'][$sId] ?? null;
                    $syncData[$sId] = ['supplier_price' => !empty($price) ? (float)$price : null];
                }
                $product->suppliers()->sync($syncData);
            }

            // Catat stok awal jika > 0
            if ($stock > 0) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'type' => 'IN',
                    'quantity' => $stock,
                    'notes' => 'Stok awal saat pendaftaran produk',
                ]);
            }
        });

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'suppliers']);
        $history = $product->stockMovements()->with('user.profile')->latest()->paginate(10);

        return view('products.show', compact('product', 'history'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $product->load('suppliers');

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku' => ['required', 'string', 'max:50', Rule::unique('products')->ignore($product->id)],
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'supplier_ids' => ['nullable', 'array'],
            'supplier_ids.*' => ['exists:suppliers,id'],
            'supplier_prices' => ['nullable', 'array'],
        ]);

        DB::transaction(function () use ($product, $validated) {
            $product->update([
                'category_id' => $validated['category_id'],
                'sku' => strtoupper($validated['sku']),
                'name' => $validated['name'],
                'price' => $validated['price'],
                'minimum_stock' => $validated['minimum_stock'],
            ]);

            // Sync many-to-many suppliers
            $syncData = [];
            if (!empty($validated['supplier_ids'])) {
                foreach ($validated['supplier_ids'] as $sId) {
                    $price = $validated['supplier_prices'][$sId] ?? null;
                    $syncData[$sId] = ['supplier_price' => !empty($price) ? (float)$price : null];
                }
            }
            $product->suppliers()->sync($syncData);
        });

        return redirect()->route('products.show', $product)
            ->with('success', 'Data produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        // Aturan Bisnis PRD 3.5: Produk yang memiliki histori tidak boleh dihapus sembarangan
        if ($product->stockMovements()->exists()) {
            return back()->with('danger', "Produk '{$product->name}' tidak dapat dihapus karena memiliki riwayat transaksi mutasi stok.");
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
