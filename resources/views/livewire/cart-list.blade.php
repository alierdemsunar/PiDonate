<div>
    @if(count($cartItems) > 0)
        <div class="cart-container">
            <div class="table-responsive">
                <table class="table table-mobile-optimized">
                    <thead>
                    <tr>
                        <th class="th-product">Ürün</th>
                        <th class="th-price">Fiyat</th>
                        <th class="th-quantity">Adet</th>
                        <th class="th-total">Toplam</th>
                        <th class="th-action"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($cartItems as $id => $item)
                        <tr>
                            <td class="td-product">
                                <div class="d-flex align-items-center">
                                    @if($item['image'] && $item['image'] != 'no-image.webp')
                                        <img src="{{ asset('assets/product/'.$item['image']) }}" alt="{{ $item['name'] }}" class="product-img">
                                    @else
                                        <div class="product-img-placeholder">
                                            <i class="bi bi-box text-muted"></i>
                                        </div>
                                    @endif
                                    <span class="product-name">{{ $item['name'] }}</span>
                                </div>
                            </td>
                            <td class="td-price">{{ number_format($item['price'], 2, ',', '.') }}</td>
                            <td class="td-quantity">
                                <div class="quantity-control">
                                    <button class="btn-quantity" type="button" wire:click="decreaseQuantity({{ $id }})" onclick="this.blur()">-</button>
                                    <input type="text" class="quantity-input" value="{{ $item['quantity'] }}" readonly>
                                    <button class="btn-quantity" type="button" wire:click="increaseQuantity({{ $id }})" onclick="this.blur()">+</button>
                                </div>
                            </td>
                            <td class="td-total">{{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}</td>
                            <td class="td-action">
                                <button wire:click="removeItem({{ $id }})" class="btn-remove" onclick="this.blur()">
                                    <i class="bi bi-x"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="cart-footer">
                <div>
                    <button wire:click="clearCart" wire:loading.attr="disabled" class="btn-clear-cart" onclick="this.blur()">
                        <i class="bi bi-trash"></i> Temizle
                    </button>
                </div>
                <div class="text-end">
                    <div class="cart-total">Toplam: <span>{{ number_format($total, 2, ',', '.') }} TL</span></div>
                    <a href="{{ route('checkout') }}" class="btn-checkout" onclick="document.querySelectorAll('#cartModal button').forEach(btn => btn.blur())">
                        Ödemeye Geç <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="empty-cart">
            <i class="bi bi-cart-x"></i>
            <h5>Sepetiniz boş</h5>
            <p>Sepetinizde henüz ürün bulunmamaktadır.</p>
            <a href="{{ route('products') }}" class="btn-shop" onclick="document.querySelectorAll('#cartModal button').forEach(btn => btn.blur())">
                Alışverişe Başla
            </a>
        </div>
    @endif
</div>
