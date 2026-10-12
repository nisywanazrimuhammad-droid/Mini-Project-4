<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;

// Root redirect
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentication Routes (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes (Staff & Admin)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/logout', [AuthController::class, 'logout']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products (Viewable by Staff & Admin)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/show/{product}', [ProductController::class, 'show'])->name('products.show');

    // Categories (Listing & View)
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

    // Suppliers (Listing & Detail)
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/show/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');

    // Transactions (Staff & Admin)
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/in', [TransactionController::class, 'showInForm'])->name('transactions.in');
    Route::post('/transactions/in', [TransactionController::class, 'storeIn']);
    Route::get('/transactions/out', [TransactionController::class, 'showOutForm'])->name('transactions.out');
    Route::post('/transactions/out', [TransactionController::class, 'storeOut']);

    // Reports
    Route::get('/reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');

    // Admin Only Routes
    Route::middleware('admin')->group(function () {
        // Products CRUD
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/edit/{product}', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        // Categories CRUD
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Suppliers CRUD
        Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
        Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

        // Users Management
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});
