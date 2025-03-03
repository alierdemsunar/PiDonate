<?php

namespace App\Livewire;

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

        // Sayfayı yenileyin (JavaScript ile)
        $this->dispatch('refreshPage');
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
        // Form validasyonu
        $this->validate();

        try {
            // Telefon numarasını temizle
            $cleanPhone = preg_replace('/\D/', '', $this->phone_no);

            // Siparişi kaydet
            $order = Order::create([
                'buyer_name' => $this->buyer_name,
                'identification_no' => $this->identification_no,
                'phone_no' => $cleanPhone,
                'email_address' => $this->email_address,
                'city' => $this->city,
                'cart_amount' => $this->cartTotal,
                'sale_amount' => $this->cartTotal,
                'payment_success' => 'no',
            ]);

            // Sipariş öğelerini kaydet (eğer order_items tablosu varsa)
            if (Schema::hasTable('order_items')) {
                foreach ($this->cartItems as $id => $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $id,
                        'product_name' => $item['name'] ?? '',
                        'product_code' => $item['code'] ?? '',
                        'quantity' => $item['quantity'] ?? 1,
                        'price' => $item['price'] ?? 0,
                        'total' => ($item['price'] ?? 0) * ($item['quantity'] ?? 1),
                    ]);
                }
            }

            // Sepeti temizle
            session()->forget('cart');

            // Başarı mesajı
            session()->flash('success', 'Siparişiniz başarıyla alındı. Teşekkür ederiz!');
            return redirect()->route('home');

        } catch (\Exception $e) {
            session()->flash('error', 'Sipariş işlemi sırasında bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
