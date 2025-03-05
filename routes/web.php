<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\Home::class)->name('home');
Route::get('/koli-ver', \App\Livewire\Products::class)->name('products');
Route::get('/iyiligi-ulastir', \App\Livewire\Checkout::class)->name('checkout');
Route::post('/payment/success', [\App\Livewire\Checkout::class, 'handlePaymentSuccess'])->name('payment.success');
Route::post('/payment/failure', [\App\Livewire\Checkout::class, 'handlePaymentFailure'])->name('payment.failure');
Route::get('/api/successful-orders', [\App\Livewire\API\SuccessfulOrders::class, 'getJsonOrders']);

Route::middleware('guest')->group(function () {
    Route::get('/panel/login', \App\Livewire\Panel\Login::class)->name('panel.login');
});

Route::middleware('auth')->group(function () {
    Route::prefix('/panel')->name('panel.')->group(function () {
        Route::post('/logout', function () {
            \Illuminate\Support\Facades\Auth::logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect()->route('panel.login');
        })->name('logout');

        Route::get('/', \App\Livewire\Panel\Dashboard::class)->name('dashboard');
        //Route::get('/orders', \App\Livewire\Panel\Orders::class)->name('orders');

    });
});

Route::get('/panel/orders', App\Livewire\API\Orders::class)
    ->name('panel.orders');

Route::get('/panel/orders/successful', function() {
    return view('livewire-wrapper', ['component' => 'api.orders', 'params' => ['type' => 'successful']]);
})->name('panel.orders.successful');

Route::get('/panel/orders/unsuccessful', function() {
    return view('livewire-wrapper', ['component' => 'api.orders', 'params' => ['type' => 'unsuccessful']]);
})->name('panel.orders.unsuccessful');
