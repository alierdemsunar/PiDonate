<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Livewire\Component;
use App\Models\Order;
use DOMDocument;

class Checkout extends Component
{
    // Form alanları
    public $buyer_name = '';
    public $identification_no = '';
    public $phone_no = '';
    public $email_address = '';
    public $city = '';
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

    private function getProductIdByName($productName)
    {
        $product = Product::where('name', $productName)->first();
        return $product ? $product->id : null;
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
            $order_uuid = Str::uuid()->toString();
            // Siparişi kaydet
            $order = Order::create([
                'order_uuid' => $order_uuid,
                'buyer_name' => $this->buyer_name,
                'buyer_ip' => request()->getClientIp(true),
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

            foreach ($this->cartItems as $item) {
                $order->items()->create([
                    'product_id' => isset($item['id']) ? $item['id'] : $this->getProductIdByName($item['name']),
                    'product_name' => $item['name'],
                    'product_code' => $item['code'] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity'],
                ]);
            }


            // Sipariş kalemleri kayıt işlemi...

            // Ödeme servisi için curl isteği
            $paymentResponse = $this->processPayment($order);

            // Ödeme sonucuna göre sipariş durumunu güncelle
            if ($paymentResponse['status'] === '3d_secure') {
                $this->dispatch('open3DSecureModal', [
                    'acsUrl' => $paymentResponse['acsUrl'],
                    'paReq' => $paymentResponse['paReq'],
                    'md' => $paymentResponse['md'],
                    'termUrl' => $paymentResponse['termUrl']
                ]);

                // Kullanıcıyı bekletme
                return;
/*
                $order->update([
                    'payment_success' => 'yes',
                    'payment_mpi_response' => json_encode($paymentResponse)
                ]);

                // Sepeti temizle
                session()->forget('cart');

                // Başarı mesajı
                session()->flash('success', 'MPI ödeme işlemi başarılı. Teşekkür ederiz!');
                return redirect()->route('home');*/
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
        $card_brand = $this->detectCardType($this->card_number);

        // Gönderilecek veriler
        $postData = [
            'PurchaseAmount' => number_format($order->sale_amount, 2, '.', ''),
            'Pan' => $this->card_number,
            'ExpiryDate' => substr($this->card_expiry_year, -2) . str_pad($this->card_expiry_month, 2, '0', STR_PAD_LEFT),
            'cvv' => $this->card_cvv,
            'BrandName' => $card_brand,
            'Currency' => '949',
            'MerchantId' => '000000037135639',
            'MerchantPassword' => 's5RKz9c8',
            'TerminalNo' => 'V1752187',
            'VerifyEnrollmentRequestId' => $order->order_uuid,
            'SuccessUrl' => route('payment.success'),
            'FailureUrl' => route('payment.failure'),
        ];

        // CURL isteği
        $ch = curl_init($paymentUrl);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type"=>"application/x-www-form-urlencoded"]);

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

        // Yanıtı logla (debug için)
        \Log::info('MPI Yanıt XML: ' . $response);

        return $this->parsePaymentResponse($response);
    }

    private function parsePaymentResponse($response)
    {
        // XML yanıtını parse et
        $result_document = new DOMDocument();
        $result_document->loadXML($response);

        // XML hata kontrolü
        $errors = libxml_get_errors();
        libxml_clear_errors();

        if (!empty($errors)) {
            return [
                'status' => 'error',
                'message' => 'XML Parsing Hatası',
                'xml_errors' => array_map(function($error) {
                    return $error->message;
                }, $errors)
            ];
        }
        $statusNode = $result_document->getElementsByTagName("Status")->item(0);
        $status = "";
        if( $statusNode != null )
            $status = $statusNode->nodeValue;

        //PAReq Bilgisi okunuyor
        $PAReqNode = $result_document->getElementsByTagName("PaReq")->item(0);
        $PaReq = "";
        if( $PAReqNode != null )
            $PaReq = $PAReqNode->nodeValue;

        //ACSUrl Bilgisi okunuyor
        $ACSUrlNode = $result_document->getElementsByTagName("ACSUrl")->item(0);
        $ACSUrl = "";
        if( $ACSUrlNode != null )
            $ACSUrl = $ACSUrlNode->nodeValue;

        //Term Url Bilgisi okunuyor
        $TermUrlNode = $result_document->getElementsByTagName("TermUrl")->item(0);
        $TermUrl = "";
        if( $TermUrlNode != null )
            $TermUrl = $TermUrlNode->nodeValue;

        //MD Bilgisi okunuyor
        $MDNode = $result_document->getElementsByTagName("MD")->item(0);
        $MD = "";
        if( $MDNode != null )
            $MD = $MDNode->nodeValue;

        //MessageErrorCode Bilgisi okunuyor
        $messageErrorCodeNode = $result_document->getElementsByTagName("MessageErrorCode")->item(0);
        $messageErrorCode = "";
        if( $messageErrorCodeNode != null )
            $messageErrorCode = $messageErrorCodeNode->nodeValue;

        // XPath kullanarak daha güvenli veri çekme

        // Status kontrolü
        if ($status == "Y") {
            return [
                'status' => '3d_secure',
                'acsUrl' => $ACSUrl,
                'paReq' => $PaReq,
                'termUrl' => $TermUrl,
                'md' => $MD,
                'raw_response' => $response
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Ödeme işlemi başarısız',
                'raw_response' => $response
            ];
        }
    }

    private function processVPOS(Order $order)
    {
        // Ödeme servisi bilgileri
        $PostUrl = 'https://onlineodeme.vakifbank.com.tr:4443/VposService/v3/Vposreq.aspx';
        $IsyeriNo = "000000037135639";
        $TerminalNo = "V1752187";
        $IsyeriSifre = "s5RKz9c8";
        $KartNo = $order->card_number;
        $expiry = substr($order->card_expiry_year, -2) . str_pad($order->card_expiry_month, 2, '0', STR_PAD_LEFT);
        $KartCvv = $order->card_cvv;
        $Tutar = $order->sale_amount;
        $SiparID = $order->payment_mpi_enrollment_request_id;
        $IslemTipi = "Sale";
        $TutarKodu = "949";
        $ClientIp = request()->getClientIp(true);
        $Eci = $order->payment_mpi_eci;
        $Cavv = $order->payment_mpi_cavv;

        // Vakıfbank'ın istediği XML formatında provizyon isteği hazırlama
        $PosXML = '<VposRequest>
                <MerchantId>'.$IsyeriNo.'</MerchantId>
                <Password>'.$IsyeriSifre.'</Password>
                <TerminalNo>'.$TerminalNo.'</TerminalNo>
                <TransactionType>'.$IslemTipi.'</TransactionType>
                <MpiTransactionId>'.$SiparID.'</MpiTransactionId>
                <CurrencyAmount>'.$Tutar.'</CurrencyAmount>
                <CurrencyCode>'.$TutarKodu.'</CurrencyCode>
                <Pan>'.$KartNo.'</Pan>
                <Expiry>'.$expiry.'</Expiry>
                <Cvv>'.$KartCvv.'</Cvv>
                <ECI>'.$Eci.'</ECI>
                <CAVV>'.$Cavv.'</CAVV>
                <TransactionDeviceSource>0</TransactionDeviceSource>
                <ClientIp>'.$ClientIp.'</ClientIp>
            </VposRequest>';

        // XML'i prmstr parametresi içinde gönder
        $postData = 'prmstr=' . $PosXML;

        // Curl ile ödeme isteği gönder
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $PostUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        // Ödeme isteği gönderiliyor
        $result = curl_exec($ch);

        curl_close($ch);
        return $result;
    }


    public function handlePaymentSuccess(Request $request)
    {
        $payment_mpi_enrollment_request_id = $request->input('VerifyEnrollmentRequestId');
        $payment_mpi_xid = $request->input('Xid');
        $payment_mpi_cavv = $request->input('Cavv');
        $payment_mpi_eci = $request->input('Eci');
        $payment_mpi_hash = $request->input('Hash');
        $payment_mpi_error_code = $request->input('ErrorCode');
        $payment_mpi_error_message = $request->input('ErrorMessage');
        $order = Order::where('order_uuid', $payment_mpi_enrollment_request_id)->first();
        if ($order) {
            $order->update([
                'payment_mpi_enrollment_request_id' => $payment_mpi_enrollment_request_id,
                'payment_mpi_xid' => $payment_mpi_xid,
                'payment_mpi_cavv' => $payment_mpi_cavv,
                'payment_mpi_eci' => $payment_mpi_eci,
                'payment_mpi_hash' => $payment_mpi_hash,
                'payment_mpi_error_code' => $payment_mpi_error_code,
                'payment_mpi_error_message' => $payment_mpi_error_message,
                'payment_mpi_response' => json_encode($request->all())
            ]);
            session()->flash('success', 'Ödemeniz başarıyla tamamlandı.');
            return $this->processVPOS($order);
        }
        session()->flash('error', 'Ödeme işlemi tamamlanamadı.');
        return redirect()->route('home');
    }

    public function handlePaymentFailure(Request $request)
    {
        dd($request->all());
        $orderUuid = $request->input('order_uuid');
        $order = Order::where('order_uuid', $orderUuid)->first();

        if ($order) {
            $order->update([
                'payment_success' => 'no',
                'payment_pos_response' => json_encode($request->all())
            ]);

            session()->flash('error', 'Ödeme işlemi başarısız oldu.');
            return redirect()->route('checkout');
        }

        session()->flash('error', 'Ödeme işlemi gerçekleştirilemedi.');
        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
