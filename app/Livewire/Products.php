<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class Products extends Component
{
    public function render()
    {
        $products = Product::where('status', 'active')->get();
        return view('livewire.products')->with([
            'products' => $products,
        ]);
    }
}
