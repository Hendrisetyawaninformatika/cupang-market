<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CupangController;

// Auth Routes
Route::get('/login', [CupangController::class, 'showLogin'])->name('login');
Route::post('/login', [CupangController::class, 'login'])->name('login.post');
Route::post('/register', [CupangController::class, 'register'])->name('register.post');
Route::post('/logout', [CupangController::class, 'logout'])->name('logout');

// Public Routes
Route::get('/', [CupangController::class, 'home'])->name('home');
Route::get('/home', [CupangController::class, 'home'])->name('home');

// Protected Routes
Route::middleware(['firebase.auth'])->group(function () {
    Route::get('/dashboard', [CupangController::class, 'dashboard'])->name('dashboard');
    Route::get('/products/create', [CupangController::class, 'create'])->name('products.create');
    Route::post('/products', [CupangController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [CupangController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [CupangController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [CupangController::class, 'destroy'])->name('products.destroy');
});