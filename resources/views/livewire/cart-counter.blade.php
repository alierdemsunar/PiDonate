<div>
    <a href="#" class="cart-menu-button d-flex align-items-center text-decoration-none gap-2" data-bs-toggle="modal" data-bs-target="#cartModal">
        <div class="position-relative">
            <i class="bi bi-cart3" style="font-size: 1.5rem;"></i>
            @if($cartCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-weight: bold; font-size: 0.75rem; transform: translate(-50%, -50%);">
                    {{ $cartCount }}
                </span>
            @endif
        </div>
        <span class="cart-text">İyilik Sepetim</span>
    </a>
</div>
