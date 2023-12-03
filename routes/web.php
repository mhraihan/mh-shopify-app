<?php

use App\Http\Controllers\CollectionsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;


// Main Page Route
Route::get('/', [DashboardController::class, 'index'])->middleware(['verify.shopify'])->name('dashboard');
Route::get('/shop', ShopController::class)->middleware(['verify.shopify'])->name('shop.index');
Route::resource("collections", CollectionsController::class)->middleware(['verify.shopify']);
Route::resource("products", ProductsController::class)->middleware(['verify.shopify']);



