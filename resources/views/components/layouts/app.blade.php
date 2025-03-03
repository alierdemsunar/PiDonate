<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Ticaret Sitesi')</title>

    <!-- Bootstrap CSS ve Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body>

<!-- Navbar -->
<div class="container">
    <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
        <div class="col-md-3 mb-2 mb-md-0">
            <a href="{{ route('home') }}" class="d-inline-flex link-body-emphasis text-decoration-none">
                <img src="{{ asset('assets/common/logo.png') }}" alt="{{ config('app.name') }} Logo">
            </a>
        </div>

        <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
            <li><a href="{{ route('home') }}" class="nav-link px-2 link-secondary"><i class="bi bi-house-door me-1"></i> Anasayfa</a></li>
            <li><a href="{{ route('products') }}" class="nav-link px-2"><i class="bi bi-box me-1"></i> Koli Ver</a></li>
        </ul>

        <div class="col-md-3 text-end">
            @livewire('cart-counter')
        </div>
    </header>
</div>

<!-- İçerik Alanı -->
<div class="container my-4">
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @yield('content')
    {{ $slot ?? '' }}
</div>

<!-- Footer -->
<hr />
<footer class="text-center">
    <p class="text-muted"><em>Ankara Halk Ekmek ve Un Fabrikası A.Ş.</em></p>
    <p>Copyright © 2025 T.C. Ankara Büyükşehir Belediyesi</p>
</footer>

<!-- Cart Modal -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cartModalLabel">İyilik Sepetim</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @livewire('cart-list')
            </div>
        </div>
    </div>
</div>

<!-- Toast Mesajları -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
    <div id="cartToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <i class="bi bi-check-circle me-2"></i>
            <strong class="me-auto">Başarılı!</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Ürün sepete eklendi.
        </div>
    </div>
</div>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

<!-- Livewire Scripts -->
@livewireScripts

<!-- Custom Scripts -->
<script>
    document.addEventListener('livewire:initialized', () => {
        // Ürün ekleme bildirimi
        Livewire.on('productAddedToCart', () => {
            const toastEl = document.getElementById('cartToast');
            if (toastEl && window.bootstrap) {
                const toast = new bootstrap.Toast(toastEl);
                toast.show();
            } else {
                alert('Ürün sepete eklendi.');
            }
        });

        // Sepet modalını açma fonksiyonu
        Livewire.on('openCartModal', () => {
            const cartModal = document.getElementById('cartModal');
            if (cartModal) {
                const bsModal = new bootstrap.Modal(cartModal);
                bsModal.show();
            }
        });

        // Sayfa yenileme fonksiyonu
        Livewire.on('refreshPage', () => {
            setTimeout(() => {
                location.reload();
            }, 500); // Modal açılmasını beklemek için biraz gecikme
        });
    });

    // Modal Odaklama Sorunu Çözümü
    document.addEventListener('DOMContentLoaded', function() {
        const cartModal = document.getElementById('cartModal');

        if (cartModal) {
            // Modal kapanmadan önce çalışacak fonksiyon
            cartModal.addEventListener('hide.bs.modal', function() {
                // Tüm odaklanabilir elementleri seç
                const focusableElements = cartModal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                focusableElements.forEach(el => {
                    el.blur();
                });

                // Modal element'ine focus olmamasını sağla
                cartModal.blur();

                // Belgeye odaklan
                document.body.focus();
            });

            // Modal açılmadan önce aria-hidden'ı temizle
            cartModal.addEventListener('show.bs.modal', function() {
                cartModal.removeAttribute('aria-hidden');
            });

            // Modal açıldığında sepet içeriğini güncelle
            cartModal.addEventListener('shown.bs.modal', function() {
                // CartList bileşenini güncelle
                Livewire.dispatch('cartUpdated');
            });
        }
    });
</script>

</body>
</html>
