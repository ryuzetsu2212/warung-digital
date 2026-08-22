<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class StaffLogin extends Component
{
    public $username = '';
    public $password = '';
    public $errorMessage = '';

    public function mount()
    {
        // Already logged in → redirect based on role
        if (Session::get('staff_logged_in') === true) {
            $role = Session::get('staff_role');
            if ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('staff.dashboard');
        }
    }

    public function login()
    {
        $key = 'staff-login:' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            $this->errorMessage = 'Terlalu banyak percobaan login. Silakan coba lagi dalam ' . ceil($seconds / 60) . ' menit.';
            return;
        }

        $this->username = trim($this->username);
        $this->password = trim($this->password);

        if (empty($this->username) || empty($this->password)) {
            $this->errorMessage = 'Username dan password harus diisi!';
            RateLimiter::hit($key, 300);
            return;
        }

        $this->username = htmlspecialchars($this->username, ENT_QUOTES, 'UTF-8');

        // Find user by username or phone with role admin or staff
        $user = User::whereIn('role', ['admin', 'staff'])
            ->where(function($query) {
                $query->where('username', $this->username)
                      ->orWhere('phone', $this->username);
            })
            ->first();

        if ($user && Hash::check($this->password, $user->password)) {
            // ✅ SECURITY FIX: Regenerate session FIRST to prevent fixation
            Session::regenerate();
            
            // Generate session fingerprint for validation
            $fingerprint = hash('sha256', implode('|', [
                request()->userAgent() ?? 'unknown',
                request()->ip(),
                request()->header('Accept-Language') ?? 'unknown'
            ]));
            
            Session::put('staff_logged_in', true);
            Session::put('staff_username', $user->name);
            Session::put('staff_user_id', $user->id);
            Session::put('staff_role', $user->role);
            Session::put('staff_login_time', now());
            Session::put('staff_last_activity', now());
            Session::put('staff_session_fingerprint', $fingerprint);

            $this->password = '';
            RateLimiter::clear($key);

            // Redirect based on role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('staff.dashboard');
        }

        $this->errorMessage = 'Username atau password salah!';
        $this->password = '';
        RateLimiter::hit($key, 300);
    }

    public function render()
    {
        return view('livewire.auth.staff-login');
    }
}
