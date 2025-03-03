<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\Home::class)->name('home');
Route::get('/koli-ver', \App\Livewire\Products::class)->name('products');
Route::get('/iyiligi-ulastir', \App\Livewire\Checkout::class)->name('checkout');
