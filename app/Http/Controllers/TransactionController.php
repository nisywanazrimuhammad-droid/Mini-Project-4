<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $productId = $request->input('product_id');
        $type = $request->input('type');

        $query = StockMovement::with(['product', 'user.profile']);

        if (!empty($productId)) {
            $query->where('product_id', $productId);
        }

        if (!empty($type) && in_array(strtoupper($type), ['IN', 'OUT'], true)) {
            $query->where('type', strtoupper($type));
        }

        $history = $query->latest()->paginate(15)->withQueryString();
        $products = Product::orderBy('name')->get();

        $filters = [
            'product_id' => $productId,
            'type' => $type,
        ];

        return view('transactions.index', compact('history', 'products', 'filters'));
    }

    public function showInForm(Request $request)
    {
        $products = Product::orderBy('name')->get();
        $selectedProductId = $request->input('product_id');

        return view('transactions.in', compact('products', 'selectedProductId'));
    }

    public function storeIn(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            DB::transaction(function () use ($validated) {
                // Lock baris produk untuk konsistensi data
                $product = Product::where('id', $validated['product_id'])->lockForUpdate()->firstOrFail();

                // Tambah stok
                $product->increment('stock', $validated['quantity']);

                // Catat mutasi IN
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'type' => 'IN',
                    'quantity' => $validated['quantity'],
                    'notes' => $validated['notes'] ?: 'Barang Masuk reguler',
                ]);
            });

            return redirect()->route('transactions.index')
                ->with('success', "Berhasil menambahkan {$validated['quantity']} unit barang masuk!");
        } catch (Exception $e) {
            return back()->with('danger', 'Gagal mencatat barang masuk: ' . $e->getMessage())->withInput();
        }
    }

    public function showOutForm(Request $request)
    {
        $products = Product::orderBy('name')->get();
        $selectedProductId = $request->input('product_id');

        return view('transactions.out', compact('products', 'selectedProductId'));
    }

    public function storeOut(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            DB::transaction(function () use ($validated) {
                // Lock baris produk untuk konsistensi data
                $product = Product::where('id', $validated['product_id'])->lockForUpdate()->firstOrFail();

                // Aturan Bisnis PRD 3.3 & 8.2: Barang keluar tidak boleh melebihi stok
                if ($validated['quantity'] > $product->stock) {
                    throw new Exception("Stok tidak mencukupi! Stok saat ini: {$product->stock}, permintaan keluar: {$validated['quantity']}.");
                }

                // Kurangi stok
                $product->decrement('stock', $validated['quantity']);

                // Catat mutasi OUT
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'type' => 'OUT',
                    'quantity' => $validated['quantity'],
                    'notes' => $validated['notes'] ?: 'Barang Keluar / Distribusi',
                ]);
            });

            return redirect()->route('transactions.index')
                ->with('success', "Berhasil mengeluarkan {$validated['quantity']} unit barang!");
        } catch (Exception $e) {
            return back()->with('danger', $e->getMessage())->withInput();
        }
    }
}
