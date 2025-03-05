<?php

namespace App\Livewire\API;

use App\Models\Order;
use Livewire\Component;

class SuccessfulOrders extends Component
{
    public $orders = [];

    public function mount()
    {
        $this->loadOrders();
    }

    public function loadOrders()
    {
        // Verileri yükleyip Livewire bileşenini güncelle
        $this->orders = Order::with('items')
            ->where('payment_3d_success', 'yes')
            ->where('payment_pos_success', 'yes')
            ->get()
            ->toArray();

        $this->dispatch('ordersUpdated'); // Livewire'ı uyararak frontend'i güncelle
    }

    public function getJsonOrders()
    {
        return response()->json(Order::with('items')
            ->where('payment_3d_success', 'yes')
            ->where('payment_pos_success', 'yes')
            ->get()
            ->toArray());
    }

    public function render()
    {
        return view('livewire.api.successful-orders');
    }
}
