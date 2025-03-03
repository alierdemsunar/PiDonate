<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Schema;

class Checkout extends Component
{
    // Form alanları - orders tablonuza uygun olarak güncellendi
    public $buyer_name;
    public $identification_no;
    public $phone_no;
    public $email_address;
    public $city;

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
        // Form validasyonu - tablonuza uygun
        $validatedData = $this->validate([
            'buyer_name' => 'required|string|max:255',
            'identification_no' => 'required|numeric|digits:11',
            'phone_no' => 'required|numeric|digits:10',
            'email_address' => 'required|email|max:255',
            'city' => 'required|string|max:31',
        ], [
            // Hata mesajları Türkçe
            'buyer_name.required' => 'Adınız ve soyadınız gereklidir.',
            'identification_no.required' => 'T.C. Kimlik numarası gereklidir.',
            'identification_no.numeric' => 'T.C. Kimlik numarası sadece rakamlardan oluşmalıdır.',
            'identification_no.digits' => 'T.C. Kimlik numarası 11 haneli olmalıdır.',
            'phone_no.required' => 'Telefon numarası gereklidir.',
            'phone_no.numeric' => 'Telefon numarası sadece rakamlardan oluşmalıdır.',
            'phone_no.digits' => 'Telefon numarası 10 haneli olmalıdır (Başında 0 olmadan giriniz).',
            'email_address.required' => 'E-posta adresi gereklidir.',
            'email_address.email' => 'Geçerli bir e-posta adresi giriniz.',
            'city.required' => 'Şehir bilgisi gereklidir.',
        ]);

        // Sepet kontrolü
        if (count($this->cartItems) === 0) {
            session()->flash('error', 'Sepetiniz boş. Lütfen önce sepetinize ürün ekleyin.');
            return redirect()->route('products');
        }

        try {
            // Siparişi kaydet - tablonuza uygun
            $order = Order::create([
                'buyer_name' => $this->buyer_name,
                'identification_no' => $this->identification_no,
                'phone_no' => $this->phone_no,
                'email_address' => $this->email_address,
                'city' => $this->city,
                'cart_amount' => $this->cartTotal,
                'sale_amount' => $this->cartTotal, // veya farklı bir hesaplama
                'payment_success' => 'no', // başlangıçta ödeme başarılı değil
            ]);

            // Sipariş öğelerini kaydet (eğer order_items tablosu varsa)
            if (Schema::hasTable('order_items')) {
                foreach ($this->cartItems as $id => $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $id,
                        'product_name' => $item['name'],
                        'product_code' => $item['code'] ?? null,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'total' => $item['price'] * $item['quantity'],
                    ]);
                }
            }

            // Sepeti temizle
            session()->forget('cart');

            // Başarı mesajı göster ve ana sayfaya yönlendir
            session()->flash('success', 'Siparişiniz başarıyla alındı. Teşekkür ederiz!');
            return redirect()->route('home');

        } catch (\Exception $e) {
            // Hata durumunda kullanıcıya bilgi ver
            session()->flash('error', 'Sipariş işlemi sırasında bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.checkout')
            ->extends('layouts.app')
            ->section('content');
    }
}
