<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MutationController;
use App\Http\Controllers\UserController;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Route Mata Uang
    Route::get('/', [CurrencyController::class, 'index'])->name('currency.index');
    Route::post('/currency', [CurrencyController::class, 'store'])->name('currency.store');
    Route::put('/currency/{id}', [CurrencyController::class, 'update'])->name('currency.update');
    Route::delete('/currency/{id}', [CurrencyController::class, 'destroy'])->name('currency.destroy');
    
    // Transaksi Routes
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');

    // Mutasi Transaksi Route
    Route::get('/mutations', [MutationController::class, 'index'])->name('mutations.index');

    // 1. Route khusus Data Nasabah
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{id}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::get('/customers/{id}/print', [CustomerController::class, 'print'])->name('customers.print');
    
    // 2. Route Ganti Password (Semua User)
    Route::get('/change-password', [UserController::class, 'changePasswordView'])->name('password.change');
    Route::put('/change-password', [UserController::class, 'updatePassword'])->name('password.update');

    // 3. Route Kelola Akun Kasir (Khusus Admin)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});