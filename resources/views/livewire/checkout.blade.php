<div class="container py-5">
    <h1 class="mb-4">Ödeme Bilgileri</h1>

    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Sipariş Özeti (Tablo Formatında) -->
    <div class="card mb-4">
        <div class="card-header text-white" style="background-color: #0d6efd;">
            <h5 class="mb-0">Sipariş Özeti</h5>
        </div>
        <div class="card-body p-0">
            @if(count($cartItems) > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-bordered mb-0">
                        <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 100px;">Ürün</th>
                            <th>Ürün Adı</th>
                            <th class="text-center" style="width: 120px;">Miktar</th>
                            <th class="text-end" style="width: 150px;">Toplam</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($cartItems as $id => $item)
                            <tr>
                                <td class="text-center align-middle">
                                    <img src="{{ asset('assets/product/'.$item['image']) }}"
                                         alt="{{ $item['name'] }}"
                                         class="img-fluid"
                                         style="max-width: 70px; max-height: 70px;">
                                </td>
                                <td class="align-middle">
                                    <strong>{{ $item['name'] }}</strong>
                                </td>
                                <td class="text-center align-middle">
                                    <strong>{{ $item['quantity'] }}</strong> adet
                                </td>
                                <td class="text-end align-middle fw-bold text-primary">
                                    {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }} ₺
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-bold fs-5">Toplam Tutar:</td>
                            <td class="text-end fw-bold text-primary fs-5">{{ number_format($cartTotal, 2, ',', '.') }} ₺</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center p-3 bg-light">
                    <button type="button" class="btn btn-outline-primary" wire:click="refreshAndOpenModal">
                        <i class="bi bi-cart-check me-1"></i> Sepeti Düzenle
                    </button>
                    <a href="#checkout-form" class="btn btn-success">
                        <i class="bi bi-arrow-right-circle me-1"></i> Ödemeye Geç
                    </a>
                </div>
            @else
                <div class="p-4 text-center">
                    <p class="mb-3"><i class="bi bi-cart-x fs-1 text-muted"></i></p>
                    <p>Sepetinizde ürün bulunmamaktadır.</p>
                    <a href="{{ route('products') }}" class="btn btn-primary mt-2">
                        <i class="bi bi-box me-1"></i> Alışverişe Başla
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4" id="checkout-form">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Teslimat Bilgileri</h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="placeOrder">
                        <!-- Ad Soyad -->
                        <div class="mb-3">
                            <label for="buyer_name" class="form-label">Ad Soyad <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('buyer_name') is-invalid @enderror"
                                   id="buyer_name" wire:model.live="buyer_name"
                                   placeholder="Adınız ve soyadınız" maxlength="255">
                            @error('buyer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <small class="text-muted">Zorunlu, en fazla 255 karakter.</small>
                                @enderror
                        </div>

                        <!-- TC Kimlik -->
                        <div class="mb-3">
                            <label for="identification_no" class="form-label">T.C. Kimlik No <span class="text-danger">*</span></label>
                            <input type="text" inputmode="numeric" pattern="[0-9]*" class="form-control @error('identification_no') is-invalid @enderror"
                                   id="identification_no" wire:model.live="identification_no"
                                   placeholder="11 haneli TC Kimlik numaranız" maxlength="11">
                            @error('identification_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <small class="text-muted">Zorunlu, 11 haneli sadece rakam.</small>
                                @enderror
                        </div>

                        <!-- Telefon -->
                        <div class="mb-3">
                            <label for="phone_no" class="form-label">Telefon <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">0</span>
                                <input type="text" class="form-control @error('phone_no') is-invalid @enderror"
                                       id="phone_no" wire:model.live="phone_no"
                                       placeholder="5XX XXX XX XX">
                            </div>
                            @error('phone_no')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @else
                                <small class="text-muted">Zorunlu, 10 haneli telefon numarası.</small>
                                @enderror
                        </div>

                        <!-- E-posta -->
                        <div class="mb-3">
                            <label for="email_address" class="form-label">E-posta <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email_address') is-invalid @enderror"
                                   id="email_address" wire:model.live.debounce.500ms="email_address"
                                   placeholder="ornek@mail.com">
                            @error('email_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <small class="text-muted">Zorunlu, geçerli bir e-posta adresi.</small>
                                @enderror
                        </div>

                        <!-- Şehir -->
                        <div class="mb-3">
                            <label for="city" class="form-label">Şehir <span class="text-danger">*</span></label>
                            <select class="form-select @error('city') is-invalid @enderror"
                                    id="city" wire:model.live="city">
                                <option value="">Şehir seçiniz</option>
                                @foreach($cities as $cityOption)
                                    <option value="{{ $cityOption }}">{{ $cityOption }}</option>
                                @endforeach
                            </select>
                            @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <small class="text-muted">Zorunlu, listeden bir şehir seçin.</small>
                                @enderror
                        </div>

                        <div class="form-text text-danger mb-3">* ile işaretli alanların doldurulması zorunludur.</div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg"
                                    wire:loading.attr="disabled"
                                    @if($errors->any()) disabled @endif>
                                <span wire:loading.remove wire:target="placeOrder">
                                    <i class="bi bi-box2-heart me-2"></i> Öde ve İyiliği Ulaştır
                                </span>
                                <span wire:loading wire:target="placeOrder">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    İşleniyor...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Bilgilendirme</h5>
                </div>
                <div class="card-body">
                    <p class="small mb-2"><i class="bi bi-shield-check me-2"></i> Kişisel bilgileriniz güvenle saklanmaktadır.</p>
                    <p class="small mb-2"><i class="bi bi-credit-card me-2"></i> Ödeme işlemleriniz SSL ile korunmaktadır.</p>
                    <p class="small mb-0"><i class="bi bi-info-circle me-2"></i> Yardıma ihtiyacınız olursa lütfen bizimle iletişime geçin.</p>
                </div>
            </div>
        </div>
    </div>
</div>
