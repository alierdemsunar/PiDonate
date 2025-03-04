<div>
    <div class="modal fade" id="threeDSecureModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">3D Secure Doğrulama</h5>
                </div>
                <div class="modal-body text-center">
                    <h4>3D Secure İşleminiz yapılıyor</h4>
                    <p>3D-Secure işleminizin doğrulama aşamasına geçebilmek için otomatik olarak yönlendirileceksiniz.</p>

                    <form id="threeDSecureForm" action="{{ $acsUrl }}" method="POST">
                        @csrf
                        <input type="hidden" name="PaReq" value="{{ $paReq }}">
                        <input type="hidden" name="TermUrl" value="{{ $termUrl }}">
                        <input type="hidden" name="MD" value="{{ $md }}">
                    </form>

                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Yükleniyor...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal ve form öğeleri
            const modalElement = document.getElementById('threeDSecureModal');

            if (!modalElement) {
                console.error('3D Secure Modal elementi bulunamadı!');
                return;
            }

            // Bootstrap'ın yüklü olduğunu kontrol et
            if (typeof bootstrap === 'undefined') {
                console.error('Bootstrap yüklenemedi!');
                return;
            }

            const modal = new bootstrap.Modal(modalElement);

            // Livewire v2 ve v3 için uyumlu olay dinleyici
            if (window.Livewire) {
                // Livewire v3
                document.addEventListener('livewire:initialized', () => {
                    Livewire.on('open3DSecureModal', () => {
                        console.log('Livewire v3: 3D Secure Modal olayı tetiklendi');
                        openModalAndSubmitForm(modal);
                    });
                });
            } else if (window.livewire) {
                // Livewire v2
                window.livewire.on('open3DSecureModal', () => {
                    console.log('Livewire v2: 3D Secure Modal olayı tetiklendi');
                    openModalAndSubmitForm(modal);
                });
            } else {
                console.error('Livewire bulunamadı!');
            }

            // Modal açma ve form gönderme işlevi
            function openModalAndSubmitForm(modal) {
                try {
                    modal.show();
                    console.log('3D Secure Modal açıldı');

                    setTimeout(() => {
                        const form = document.getElementById('threeDSecureForm');
                        if (form) {
                            console.log('3D Secure Form gönderiliyor...');
                            form.submit();
                        } else {
                            console.error('3D Secure Form elementi bulunamadı!');
                        }
                    }, 1000);
                } catch (error) {
                    console.error('3D Secure Modal açılırken hata oluştu:', error);
                }
            }
        });
    </script>
</div>
