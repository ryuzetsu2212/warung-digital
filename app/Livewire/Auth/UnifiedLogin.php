<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class UnifiedLogin extends Component
{
    public $identifier = ''; // nama atau no hp
    public $password = '';
    public $remember = false;

    protected $rules = [
        'identifier' => 'required|string',
        'password' => 'required|string',
    ];

    protected $messages = [
        'identifier.required' => 'Nama atau Nomor HP harus diisi',
        'password.required' => 'Password harus diisi',
    ];

    public function login()
    {
        $this->validate();

        // Rate limiting
        $key = Str::lower($this->identifier) . '|' . request()->ip();
        
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            session()->flash('error', "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.");
            return;
        }

        // Cek apakah ini staff login (hardcoded credentials)
        $validStaff = [
            'admin' => 'admin123',
            'staff' => 'staff123',
            'dapur' => 'dapur123',
        ];

        if (isset($validStaff[$this->identifier]) && $validStaff[$this->identifier] === $this->password) {
            // Clear rate limiter on successful login
            RateLimiter::clear($key);

            // Set staff session
            Session::put('staff_logged_in', true);
            Session::put('staff_username', $this->identifier);
            Session::put('user_type', 'staff');

            // Regenerate session
            request()->session()->regenerate();

            // Generate session fingerprint for validation
            $fingerprint = hash('sha256', implode('|', [
                request()->userAgent() ?? 'unknown',
                request()->header('Accept-Language') ?? 'unknown'
            ]));
            Session::put('staff_session_fingerprint', $fingerprint);

            // Redirect to staff dashboard
            return redirect()->route('staff.dapur');
        }

        // Coba login sebagai customer - cek database
        $user = User::where(function($query) {
            $query->where('phone', $this->identifier)
                  ->orWhere('name', $this->identifier);
        })->first();

        if (!$user || !Hash::check($this->password, $user->password)) {
            RateLimiter::hit($key, 300); // 5 minutes
            session()->flash('error', 'Nama/Nomor HP atau password salah.');
            return;
        }

        // Clear rate limiter on successful login
        RateLimiter::clear($key);

        // Login user
        Auth::login($user, $this->remember);

        // Regenerate session
        request()->session()->regenerate();

        // Set session flag
        Session::put('user_type', 'customer');
        Session::put('user_id', $user->id);

        // Redirect based on role
        if (isset($user->role) && $user->role === 'staff') {
            return redirect()->route('staff.dapur');
        }

        // Redirect to reservations page for customer
        return redirect()->route('customer.reservations.create');
    }

    public function render()
    {
        return view('livewire.auth.unified-login');
    }
}