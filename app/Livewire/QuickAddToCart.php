<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class QuickAddToCart extends Component
{
    public $productId;

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
            $cart[$this->productId]['quantity'] += 1;
        } else {
            // Ürün yoksa yeni ekle
            $cart[$this->productId] = [
                "code" => $product->code,
                "name" => $product->name,
                "quantity" => 1,
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
    }

    public function render()
    {
        return view('livewire.quick-add-to-cart');
    }
}
