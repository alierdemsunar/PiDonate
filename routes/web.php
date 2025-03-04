<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\Home::class)->name('home');
Route::get('/koli-ver', \App\Livewire\Products::class)->name('products');
Route::get('/iyiligi-ulastir', \App\Livewire\Checkout::class)->name('checkout');
Route::post('/payment/success', [\App\Livewire\Checkout::class, 'handlePaymentSuccess'])->name('payment.success');
Route::post('/payment/failure', [\App\Livewire\Checkout::class, 'handlePaymentFailure'])->name('payment.failure');
