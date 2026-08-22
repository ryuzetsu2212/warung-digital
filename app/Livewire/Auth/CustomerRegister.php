<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class CustomerRegister extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    public $phone;

    protected $rules = [
        'name' => 'required|string|max:255',
        'phone' => 'required|numeric|digits_between:10,15|unique:users,phone',
        'email' => 'nullable|email|unique:users,email',
        'password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
    ];

    protected $messages = [
        'phone.required' => 'Nomor telepon wajib diisi',
        'phone.numeric' => 'Nomor telepon hanya boleh berisi angka',
        'phone.digits_between' => 'Nomor telepon harus 10-15 digit',
        'phone.unique' => 'Nomor telepon sudah terdaftar',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
        'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, dan angka.',
    ];

    public function register()
    {
        $this->validate();

        // ✅ SECURITY FIX: Stricter rate limiting (3 attempts per 10 minutes)
        $key = 'register:' . request()->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $minutes = ceil(RateLimiter::availableIn($key) / 60);
            $this->addError('rate_limit', 'Terlalu banyak percobaan registrasi. Silakan coba lagi dalam ' . $minutes . ' menit.');
            return;
        }
        RateLimiter::hit($key, 600); // 10 minutes

        $user = User::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'customer',
        ]);

        // ✅ SECURITY FIX: Session regeneration & fingerprinting
        Auth::login($user);
        session()->regenerate();
        
        // Generate session fingerprint
        $fingerprint = hash('sha256', implode('|', [
            request()->userAgent() ?? 'unknown',
            request()->ip(),
            request()->header('Accept-Language') ?? 'unknown'
        ]));
        session(['customer_session_fingerprint' => $fingerprint]);
        
        // Clear rate limiter on successful registration
        RateLimiter::clear($key);

        return redirect()->route('customer.reservations.create');
    }

    public function render()
    {
        return view('livewire.auth.customer-register');
    }
}