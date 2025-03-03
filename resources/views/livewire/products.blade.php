<div class="container">
    <div class="row g-2">
        @foreach($products as $product)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 border-primary shadow-sm rounded">
                    <img src="{{ asset('assets/product/'.$product->image) }}" class="card-img-top" alt="{{ $product->name }}" />
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <div class="price-tag">
                            @if($product->sale_price > 0)
                                <div class="original-price">{{ number_format($product->price, 2, ',', '.') }} ₺</div>
                                <div class="current-price">{{ number_format($product->sale_price, 2, ',', '.') }} ₺</div>
                            @else
                                <div class="current-price">{{ number_format($product->price, 2, ',', '.') }} ₺</div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-grid mt-2">
                            @livewire('quick-add-to-cart', ['productId' => $product->id], key('quick-add-'.$product->id))
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
