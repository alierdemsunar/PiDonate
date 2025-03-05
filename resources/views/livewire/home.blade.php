<div class="container">
    <!-- Yardım Sayacı -->
    <div class="row g-4">
        <!-- Toplam Koli Sayacı -->
        <div class="col-md-6">
            <div class="card h-100 border-info shadow-sm rounded">
                <div class="card-body text-center">
                    <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4 counters">
                        <i class="bi bi-boxes bi-xl-size"></i>
                    </div>
                    <h2 class="card-title fw-bold text-info">{{ $totalOrders }}</h2>
                    <p class="card-text text-info"><i class="bi bi-boxes me-1"></i> Toplam İyilik Kolisi</p>
                </div>
            </div>
        </div>
        <!-- Toplam Tutar Sayacı -->
        <div class="col-md-6">
            <div class="card h-100 border-success shadow-sm rounded">
                <div class="card-body text-center">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4 counters">
                        <i class="bi bi-house-heart bi-xl-size"></i>
                    </div>
                    <h2 class="card-title fw-bold text-success">{{ number_format($totalAmount, 2, ',', '.') }} ₺</h2>
                    <p class="card-text text-success"><i class="bi bi-house-heart me-1"></i> Toplam İyilik Tutarı</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Harita verisi -->
    @livewire('map')
    <!-- Anasayfa slider -->
    <div id="home-slider" class="carousel slide mt-4">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('assets/slider/banner-03.jpg') }}" class="d-block w-100" alt="{{ config('app.name') }} Banner">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('assets/slider/banner-02.jpg') }}" class="d-block w-100" alt="{{ config('app.name') }} Banner">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('assets/slider/banner-01.jpg') }}" class="d-block w-100" alt="{{ config('app.name') }} Banner">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#home-slider" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Önceki</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#home-slider" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Sonraki</span>
        </button>
    </div>
    <!-- Adımlar -->
    <div class="row g-4 py-4">
        <div class="col-12 col-md-4 col-lg-3">
            <div class="card h-100 border-primary shadow-sm rounded">
                <div class="card-body text-center">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4 steps">
                        <i class="bi bi-box bi-xl-size"></i>
                    </div>
                    <h3 class="card-title fw-bold text-primary">1. Adım</h3>
                    <p class="card-text">Menüden <strong class="text-primary">"<i class="bi bi-box me-1"></i> Koli Ver"</strong> butonuna basınız ve çıkan ürünleri inceleyiniz. Yardım kolinize eklemek istediğiniz ürünleri <strong class="text-primary">"<i class="bi bi-cart-plus me-1"></i> Koliye Ekle"</strong> butonuna basarak sepetinize atınız.</p>
                    <p class="text-muted small fst-italic mt-2">* Ürünlerin detaylarını görmek için ürünün resmine tıklayabilirsiniz.</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-3">
            <div class="card h-100 border-success shadow-sm rounded">
                <div class="card-body text-center">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4 steps">
                        <i class="bi bi-cart-check bi-xl-size"></i>
                    </div>
                    <h3 class="card-title fw-bold text-success">2. Adım</h3>
                    <p class="card-text">Yardım kolinize eklenen ürünlerinizi kontrol etmek için sağ üst köşedeki <strong class="text-success">"<i class="bi bi-cart3 me-1"></i> İyilik Sepetim"</strong> butonuna tıklayınız.</p>
                    <p class="text-muted small fst-italic mt-2">* Yardım kolinizdeki ürünleri buradan kontrol ederek dilediğiniz gibi düzenleyebilirsiniz.</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-3">
            <div class="card h-100 border-warning shadow-sm rounded">
                <div class="card-body text-center">
                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4 steps">
                        <i class="bi bi-box-seam bi-xl-size"></i>
                    </div>
                    <h3 class="card-title fw-bold text-warning">3. Adım</h3>
                    <p class="card-text">Yardım koliniz hazır olduğunda <strong class="text-warning">"<i class="bi bi-cart3 me-1"></i> İyilik Sepetim"</strong> butonuna tıklayarak açılan pencereden <strong class="text-warning">"<i class="bi bi-bag-heart me-1"></i> İyiliği Ulaştır"</strong> butonuna basınız.
                    <p class="text-muted small fst-italic mt-2">* İyiliği ulaştır butonuna bastıktan sonra sepette .<span class="badge bg-warning">%15 indirim</span> uygulandığını görebilirsiniz. </p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-3">
            <div class="card h-100 border-danger shadow-sm rounded">
                <div class="card-body text-center">
                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4 steps">
                        <i class="bi bi-balloon-heart bi-xl-size"></i>
                    </div>
                    <h3 class="card-title fw-bold text-danger">4. Adım</h3>
                    <p class="card-text">Son adımınızda sizden istenen bilgileri eksiksiz girerek <strong class="text-danger">"<i class="bi bi-box2-heart me-1"></i> Öde ve İyiliği Ulaştır"</strong> butonuna tıklayınız. Ödeme işleminizi başarıyla tamamladıktan sonra yardımınızın ulaşacağı ihtiyaç sahibi aile bilgilendirilecektir.</p>
                    <p class="text-muted small fst-italic mt-2">* İletmiş olduğunuz bilgiler tarafınıza fatura düzenlenmesi için gereklidir. 3. kişi veya kurumlarla asla paylaşılmayacaktır.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Ürünler sayfasına yönlendirme -->
    <div class="row">
        <div class="col-md-12">
            <div class="d-grid">
                <a href="{{ route('products') }}" class="btn btn-danger" style=" font-size: 1.5rem">  <i class="bi bi-heart-fill" ></i> İyilik Hareketine Katılın</a>
            </div>
        </div>
    </div>
</div>
