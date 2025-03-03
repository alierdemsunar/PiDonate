<?php

namespace App\Livewire;

use Livewire\Component;

class CartCounter extends Component
{
    public $cartCount = 0;
    public $cartTotal = 0;

    protected $listeners = ['cartUpdated' => 'updateCart'];

    public function mount()
    {
        $this->updateCart();
    }

    public function updateCart()
    {
        $cart = session()->get('cart', []);
        $this->cartCount = count($cart);

        // Toplam tutarı hesapla
        $this->cartTotal = 0;
        foreach ($cart as $item) {
            $this->cartTotal += $item['price'] * $item['quantity'];
        }
    }

    public function render()
    {
        return view('livewire.cart-counter');
    }
}
