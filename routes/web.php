<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;

Route::prefix('category')->name('category.')->group(function () {
    Route::get('/', [CategoryController::class,'index'])->name('index');

    Route::prefix('change/order')->name('change.order.')->group(function () {
        Route::any('/up/{category}', [CategoryController::class,'changeOrderUp'])->name('up');
        Route::any('/down/{category}', [CategoryController::class,'changeOrderDown'])->name('down');
    });
});

Route::prefix('order')->name('order.')->group(function () {
    Route::get('/report', [OrderController::class,'getReport'])->name('report');
});

Route::prefix('product')->name('product.')->group(function () {
    Route::prefix('change/order')->name('change.order.')->group(function () {
        Route::any('/up/{product}', [ProductController::class,'changeOrderUp'])->name('up');
        Route::any('/down/{product}', [ProductController::class,'changeOrderDown'])->name('down');
    });
});