<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class Home extends Component
{
    public $totalOrders;
    public $totalAmount;

    public function mount()
    {
        // 3D ve POS ödeme başarılı olan siparişlerin toplam sayısı
        $this->totalOrders = Order::where('payment_3d_success', 'yes')
            ->where('payment_pos_success', 'yes')
            ->count();

        // 3D ve POS ödeme başarılı olan siparişlerin toplam tutarı
        $this->totalAmount = Order::where('payment_3d_success', 'yes')
            ->where('payment_pos_success', 'yes')
            ->sum('sale_amount');
    }

    public function render()
    {
        return view('livewire.home');
    }
}
