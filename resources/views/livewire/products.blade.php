<div class="container">
    <div class="row g-3">
        @foreach($products as $product)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ asset('assets/product/'.$product->image) }}" alt="{{ $product->name }}">
                    </div>
                    <div class="product-info">
                        <h5 class="product-title">{{ $product->name }}</h5>
                        <div class="product-price">
                            @if($product->sale_price > 0)
                                <span class="original-price">{{ number_format($product->price, 2, ',', '.') }} ₺</span>
                                <span class="sale-price">{{ number_format($product->sale_price, 2, ',', '.') }} ₺</span>
                            @else
                                <span class="sale-price">{{ number_format($product->price, 2, ',', '.') }} ₺</span>
                            @endif
                        </div>
                    </div>
                    <div class="product-actions">
                        @livewire('quick-add-to-cart', ['productId' => $product->id], key('quick-add-'.$product->id))
                        <button class="info-button" data-bs-toggle="modal" data-product-name="{{ $product->name }}" data-bs-target="#productModal-{{ $product->id }}" data-product-id="{{ $product->id }}">
                            <i class="bi bi-info-circle"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
