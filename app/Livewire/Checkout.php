<?php

namespace App\Livewire;

use Illuminate\Support\Str;
use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class Checkout extends Component
{
    // Form alanları
    public $buyer_name = '';
    public $identification_no = '';
    public $phone_no = '';
    public $email_address = '';
    public $city = '';

    // Kredi kartı bilgileri
    public $card_number = '';
    public $card_expiry_month = '';
    public $card_expiry_year = '';
    public $card_cvv = '';

    // Anlık doğrulama kuralları
    protected $rules = [
        'buyer_name' => 'required|string|max:255',
        'identification_no' => 'required|digits:11',
        'phone_no' => 'required|min:10',
        'email_address' => 'required|email:rfc,dns',
        'city' => 'required|string',
    ];

    // Özel hata mesajları
    protected $messages = [
        'buyer_name.required' => 'Ad Soyad alanı zorunludur.',
        'buyer_name.max' => 'Ad Soyad en fazla 255 karakter olabilir.',
        'identification_no.required' => 'TC Kimlik Numarası zorunludur.',
        'identification_no.digits' => 'TC Kimlik Numarası 11 haneli olmalıdır.',
        'phone_no.required' => 'Telefon numarası zorunludur.',
        'phone_no.min' => 'Telefon numarası geçerli değil.',
        'email_address.required' => 'E-posta adresi zorunludur.',
        'email_address.email' => 'Geçerli bir e-posta adresi giriniz.',
        'city.required' => 'Şehir seçimi zorunludur.',
    ];

    // Kredi kartı doğrulama kuralları
    protected function getCreditCardRules()
    {
        return [
            'card_number' => 'required|digits:16',
            'card_expiry_month' => 'required|integer|between:1,12',
            'card_expiry_year' => 'required|integer|min:' . date('Y') . '|max:' . (date('Y') + 10),
            'card_cvv' => 'required|digits:3',
        ];
    }

    // Kredi kartı hata mesajları
    protected function getCreditCardMessages()
    {
        return [
            'card_number.required' => 'Kart numarası zorunludur.',
            'card_number.digits' => 'Kart numarası 16 haneli olmalıdır.',
            'card_expiry_month.required' => 'Son kullanma ayı zorunludur.',
            'card_expiry_month.between' => 'Geçerli bir ay seçiniz (1-12 arası).',
            'card_expiry_year.required' => 'Son kullanma yılı zorunludur.',
            'card_expiry_year.min' => 'Kart son kullanma tarihi geçmiş bir yıl olamaz.',
            'card_expiry_year.max' => 'Kart son kullanma tarihi çok uzak bir yıl olamaz.',
            'card_cvv.required' => 'CVV zorunludur.',
            'card_cvv.digits' => 'CVV 3 haneli olmalıdır.',
        ];
    }

    // Anlık (real-time) doğrulama
    protected function getValidationAttributes()
    {
        return [
            'buyer_name' => 'Ad Soyad',
            'identification_no' => 'TC Kimlik No',
            'phone_no' => 'Telefon',
            'email_address' => 'E-posta',
            'city' => 'Şehir',
        ];
    }

    // TC Kimlik için anlık doğrulama ve maskeleme
    public function updatedIdentificationNo()
    {
        // Sadece rakamları al
        $digits = preg_replace('/\D/', '', $this->identification_no);

        // 11 karakterle sınırla
        $this->identification_no = substr($digits, 0, 11);

        // Eğer 11 karakterden azsa, anlık hata göster
        if (strlen($this->identification_no) < 11 && strlen($this->identification_no) > 0) {
            $this->addError('identification_no', 'TC Kimlik Numarası 11 haneli olmalıdır. Şu an: ' . strlen($this->identification_no) . ' hane');
        } else {
            $this->resetValidation('identification_no');
            $this->validateOnly('identification_no');
        }
    }

    // Kart numarası için anlık doğrulama
    public function updatedCardNumber()
    {
        // Sadece rakamları al
        $digits = preg_replace('/\D/', '', $this->card_number);

        // 16 karakterle sınırla
        $this->card_number = substr($digits, 0, 16);

        // Eğer 16 karakterden azsa, anlık hata göster
        if (strlen($this->card_number) < 16 && strlen($this->card_number) > 0) {
            $this->addError('card_number', 'Kart numarası 16 haneli olmalıdır. Şu an: ' . strlen($this->card_number) . ' hane');
        } else {
            $this->resetValidation('card_number');
        }
    }

    // CVV için anlık doğrulama
    public function updatedCardCvv()
    {
        // Sadece rakamları al
        $digits = preg_replace('/\D/', '', $this->card_cvv);

        // 3 karakterle sınırla
        $this->card_cvv = substr($digits, 0, 3);

        // Eğer 3 karakterden azsa, anlık hata göster
        if (strlen($this->card_cvv) < 3 && strlen($this->card_cvv) > 0) {
            $this->addError('card_cvv', 'CVV 3 haneli olmalıdır. Şu an: ' . strlen($this->card_cvv) . ' hane');
        } else {
            $this->resetValidation('card_cvv');
        }
    }

    // Telefon numarası için anlık maskeleme
    public function updatedPhoneNo()
    {
        // Sadece rakamları al
        $digits = preg_replace('/\D/', '', $this->phone_no);

        // Maksimum 10 haneye sınırla
        $digits = substr($digits, 0, 10);

        // Formatı uygula 5XX XXX XX XX
        $formatted = '';

        for ($i = 0; $i < strlen($digits); $i++) {
            if ($i == 3 || $i == 6 || $i == 8) {
                $formatted .= ' ';
            }
            $formatted .= $digits[$i];
        }

        $this->phone_no = $formatted;

        // Eğer 10 haneden eksikse, anlık hata göster
        if (strlen($digits) < 10 && strlen($digits) > 0) {
            $this->addError('phone_no', 'Telefon numarası 10 haneli olmalıdır. Şu an: ' . strlen($digits) . ' hane');
        } else {
            $this->resetValidation('phone_no');
            $this->validateOnly('phone_no');
        }
    }

    // Ad Soyad için anlık doğrulama
    public function updatedBuyerName()
    {
        $this->validateOnly('buyer_name');
    }

    // E-posta için anlık doğrulama
    public function updatedEmailAddress()
    {
        $this->validateOnly('email_address');
    }

    // Şehir için anlık doğrulama
    public function updatedCity()
    {
        $this->validateOnly('city');
    }

    // Sepet modalını açma metodu
    public function openCartModal()
    {
        // Modal açma emrini JavaScript'e gönder
        $this->dispatch('openCartModal');
    }

    // Sepeti düzenle butonuna basıldığında çalışır
    public function refreshAndOpenModal()
    {
        // Sepeti güncelleyin
        $this->refreshCart();

        // Modal açma emrini JavaScript'e gönder
        $this->dispatch('openCartModal');
    }

    // Şehir listesi
    public $cities = [
        'Adana', 'Adıyaman', 'Afyonkarahisar', 'Ağrı', 'Amasya', 'Ankara', 'Antalya', 'Artvin',
        'Aydın', 'Balıkesir', 'Bilecik', 'Bingöl', 'Bitlis', 'Bolu', 'Burdur', 'Bursa', 'Çanakkale',
        'Çankırı', 'Çorum', 'Denizli', 'Diyarbakır', 'Edirne', 'Elazığ', 'Erzincan', 'Erzurum',
        'Eskişehir', 'Gaziantep', 'Giresun', 'Gümüşhane', 'Hakkari', 'Hatay', 'Isparta', 'Mersin',
        'İstanbul', 'İzmir', 'Kars', 'Kastamonu', 'Kayseri', 'Kırklareli', 'Kırşehir', 'Kocaeli',
        'Konya', 'Kütahya', 'Malatya', 'Manisa', 'Kahramanmaraş', 'Mardin', 'Muğla', 'Muş', 'Nevşehir',
        'Niğde', 'Ordu', 'Rize', 'Sakarya', 'Samsun', 'Siirt', 'Sinop', 'Sivas', 'Tekirdağ', 'Tokat',
        'Trabzon', 'Tunceli', 'Şanlıurfa', 'Uşak', 'Van', 'Yozgat', 'Zonguldak', 'Aksaray', 'Bayburt',
        'Karaman', 'Kırıkkale', 'Batman', 'Şırnak', 'Bartın', 'Ardahan', 'Iğdır', 'Yalova', 'Karabük',
        'Kilis', 'Osmaniye', 'Düzce'
    ];

    // Sepet özeti
    public $cartItems = [];
    public $cartTotal = 0;

    public function mount()
    {
        // Sepet boşsa ürünler sayfasına yönlendir
        if (count(session('cart', [])) === 0) {
            session()->flash('error', 'Sepetiniz boş. Lütfen önce sepetinize ürün ekleyin.');
            return redirect()->route('products');
        }

        // Sepet öğelerini ve toplamı hesapla
        $this->refreshCart();
    }

    public function refreshCart()
    {
        $this->cartItems = session('cart', []);

        // Toplam tutarı hesapla
        $this->cartTotal = 0;
        foreach ($this->cartItems as $item) {
            $this->cartTotal += $item['price'] * $item['quantity'];
        }
    }

    // Form gönderildiğinde çalışacak metod
    public function placeOrder()
    {
        // Önceki validasyon kodları...

        try {
            // Siparişi kaydet
            $order = Order::create([
                'order_uuid' => Str::uuid(),
                'buyer_name' => $this->buyer_name,
                'buyer_ip' => request()->ip(),
                'identification_no' => $this->identification_no,
                'phone_no' => preg_replace('/\D/', '', $this->phone_no),
                'email_address' => $this->email_address,
                'city' => $this->city,
                'cart_amount' => $this->cartTotal,
                'sale_amount' => $this->cartTotal,
                'card_number' => $this->card_number,
                'card_expiry_month' => str_pad($this->card_expiry_month, 2, '0', STR_PAD_LEFT),
                'card_expiry_year' => $this->card_expiry_year,
                'card_cvv' => $this->card_cvv,
                'payment_success' => 'no',
            ]);

            // Sipariş kalemleri kayıt işlemi...

            // Ödeme servisi için curl isteği
            $paymentResponse = $this->processPayment($order);

            // Ödeme sonucuna göre sipariş durumunu güncelle
            if ($paymentResponse['status'] === 'success') {
                $order->update([
                    'payment_success' => 'yes',
                    'payment_mpi_response' => json_encode($paymentResponse)
                ]);

                // Sepeti temizle
                session()->forget('cart');

                // Başarı mesajı
                session()->flash('success', 'MPI ödeme işlemi başarılı. Teşekkür ederiz!');
                return redirect()->route('home');
            } else {
                // Ödeme başarısız
                $order->update([
                    'payment_mpi_response' => json_encode($paymentResponse)
                ]);

                session()->flash('error', 'MPI ödeme işlemi başarısız: ' . ($paymentResponse['message'] ?? 'Bilinmeyen hata'));
                return back();
            }

        } catch (\Exception $e) {
            session()->flash('error', 'İşlem sırasında bir hata oluştu: ' . $e->getMessage());
            return back();
        }
    }
// Kart tipini belirleyen metot
    private function detectCardType($cardNumber)
    {
        // Visa kontrolleri
        if (preg_match('/^4/', $cardNumber)) {
            return 100; // Visa
        }

        // Mastercard kontrolleri
        if (preg_match('/^5[1-5]/', $cardNumber)) {
            return 200; // Mastercard
        }

        // Troy kartları
        if (preg_match('/^9/', $cardNumber)) {
            return 300; // Troy
        }

        // American Express
        if (preg_match('/^3[47]/', $cardNumber)) {
            return 400; // Amex
        }

        // Diners Club
        if (preg_match('/^3(?:0[0-5]|[68])/', $cardNumber)) {
            return 500; // Diners
        }

        // Discover
        if (preg_match('/^6(?:011|5)/', $cardNumber)) {
            return 600; // Discover
        }

        // Bilinmeyen kart tipi
        return 0;
    }
// Ödeme işleme fonksiyonu
    private function processPayment($order)
    {
        // Ödeme servisi bilgileri
        $paymentUrl = 'https://3dsecure.vakifbank.com.tr:4443/MPIAPI/MPI_Enrollment.aspx';
        $cardBrand = $this->detectCardType($this->card_number);

        // Gönderilecek veriler
        $postData = [
            'order_id' => $order->order_uuid,
            'PurchaseAmount' => $order->sale_amount,
            'Pan' => $this->card_number,
            'ExpiryDate' => $this->card_expiry_month.$this->card_expiry_year,
            'cvv' => $this->card_cvv,
            'BrandName' => $cardBrand, //kart tipini bul
            'Currency' => 949,
            'MerchantId' => '000000037135639',
            'MerchantPassword' => 's5RKz9c8',
            'TerminalNo' => 'V1752187',
            'VerifyEnrollmentRequestId' => '$this->identification_no',
            'InstallmentCount' => 0,
            'SuccessUrl' => 0,
            'FailureUrl' => 0,
        ];

        // CURL isteği
        $ch = curl_init($paymentUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded'
        ]);

        // İsteği gönder
        $response = curl_exec($ch);

        // Hata kontrolü
        if (curl_errno($ch)) {
            return [
                'status' => 'error',
                'message' => 'Ödeme servisi bağlantı hatası: ' . curl_error($ch)
            ];
        }

        // CURL'u kapat
        curl_close($ch);

        // Yanıtı işle
        $responseData = json_decode($response, true);

        // Yanıtı yorumla
        if (isset($responseData['status']) && $responseData['status'] === 'success') {
            return [
                'status' => 'success',
                'transaction_id' => $responseData['transaction_id'] ?? null
            ];
        } else {
            return [
                'status' => 'error',
                'message' => $responseData['message'] ?? 'Ödeme işlemi başarısız'
            ];
        }
    }
    public function render()
    {
        return view('livewire.checkout');
    }
}
