<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductController::class,'index']);
Route::get('/product/{id}', [ProductController::class,'checkout'])->name('checkout');
Route::post('/pay', [PaymentController::class,'pay'])->name('pay');
Route::get('/payment/callback/{gateway}/{transaction}', [PaymentController::class,'callback'])->name('payment.callback');


