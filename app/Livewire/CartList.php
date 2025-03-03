<?php

namespace App\Livewire;

use Livewire\Component;

class CartList extends Component
{
    public $cartItems = [];

    protected $listeners = ['cartUpdated' => 'updateCartItems'];

    public function mount()
    {
        $this->updateCartItems();
    }

    public function updateCartItems()
    {
        $this->cartItems = session()->get('cart', []);
    }

    public function increaseQuantity($productId)
    {
        $cart = session()->get('cart', []);

        if(isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
            session()->put('cart', $cart);
            $this->updateCartItems();

            // CartCounter bileşenini yenile
            $this->dispatch('cartUpdated');
        }
    }

    public function decreaseQuantity($productId)
    {
        $cart = session()->get('cart', []);

        if(isset($cart[$productId]) && $cart[$productId]['quantity'] > 1) {
            $cart[$productId]['quantity']--;
            session()->put('cart', $cart);
            $this->updateCartItems();

            // CartCounter bileşenini yenile
            $this->dispatch('cartUpdated');
        }
    }

    public function removeItem($productId)
    {
        $cart = session()->get('cart', []);

        if(isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
            $this->updateCartItems();

            // CartCounter bileşenini yenile
            $this->dispatch('cartUpdated');
        }
    }

    public function clearCart()
    {
        session()->forget('cart');
        $this->updateCartItems();

        // CartCounter bileşenini yenile
        $this->dispatch('cartUpdated');
    }

    // Erişilebilirlik sorunu için yeni metod ekleniyor
    public function resetFocus()
    {
        $this->dispatch('resetModalFocus');
    }

    public function render()
    {
        $total = 0;
        foreach($this->cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('livewire.cart-list', [
            'total' => $total
        ]);
    }
}
