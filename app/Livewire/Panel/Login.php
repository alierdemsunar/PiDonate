<?php

namespace App\Livewire\Panel;

use Livewire\Component;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    protected $messages = [
        'email.required' => 'Email adresi gereklidir.',
        'email.email' => 'Geçerli bir email adresi giriniz.',
        'password.required' => 'Şifre gereklidir.',
        'password.min' => 'Şifre en az 6 karakter olmalıdır.',
    ];

    public function authenticate()
    {
        $this->validate();

        if (\Illuminate\Support\Facades\Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            return redirect()->intended(route('panel.dashboard'));
        }

        $this->addError('email', 'Verilen bilgilerle eşleşen bir kullanıcı bulunamadı!');
    }

    public function render()
    {
        return view('livewire.panel.login');
    }
}
