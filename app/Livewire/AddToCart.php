<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class AddToCart extends Component
{
    public $productId;
    public $quantity = 1;

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function addToCart()
    {
        $product = Product::findOrFail($this->productId);

        // Ürünün fiyatını belirle (indirimli fiyat varsa onu kullan)
        $price = $product->sale_price > 0 ? $product->sale_price : $product->price;

        // Sepeti session'dan al
        $cart = session()->get('cart', []);

        // Ürün sepette var mı kontrol et
        if(isset($cart[$this->productId])) {
            // Ürün varsa miktarını artır
            $cart[$this->productId]['quantity'] += $this->quantity;
        } else {
            // Ürün yoksa yeni ekle
            $cart[$this->productId] = [
                "code" => $product->code,
                "name" => $product->name,
                "quantity" => $this->quantity,
                "price" => $price,
                "image" => $product->image
            ];
        }

        // Session'a sepeti kaydet
        session()->put('cart', $cart);

        // Cart Counter bileşenini yenile
        $this->dispatch('cartUpdated');

        // Kullanıcıya bildirim göster
        $this->dispatch('productAddedToCart');

        // Miktarı sıfırla
        $this->quantity = 1;
    }

    public function render()
    {
        return view('livewire.add-to-cart');
    }
}
