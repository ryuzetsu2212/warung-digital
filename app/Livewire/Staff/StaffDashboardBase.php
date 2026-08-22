<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use App\Livewire\Concerns\HandlesTableAutoStatus;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

abstract class StaffDashboardBase extends Component
{
    use HandlesTableAutoStatus;

    public $isLoggedIn = false;
    public $username = '';
    public $password = '';
    public $errorMessage = '';
    public $successMessage = '';

    public function mount()
    {
        $this->checkAutoTableStatus();
        // Check if staff is logged in
        if (Session::has('staff_logged_in') && Session::get('staff_logged_in') === true) {
            $this->isLoggedIn = true;
        }
    }

    public function login()
    {
        // Rate limiting
        $key = 'staff-login:' . request()->ip();
        
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            $this->errorMessage = 'Terlalu banyak percobaan login. Silakan coba lagi dalam ' . ceil($seconds / 60) . ' menit.';
            return;
        }

        // Input validation
        $this->username = trim($this->username);
        $this->password = trim($this->password);

        if (empty($this->username) || empty($this->password)) {
            $this->errorMessage = 'Username dan password harus diisi!';
            RateLimiter::hit($key, 300);
            return;
        }

        // Sanitize username input to prevent XSS
        $this->username = htmlspecialchars($this->username, ENT_QUOTES, 'UTF-8');

        // Verify credentials using environment variables
        $validUsername = env('STAFF_USERNAME', 'Admin');
        $validPassword = env('STAFF_PASSWORD', '#Admin123');

        if ($this->username === $validUsername && $this->password === $validPassword) {
            // Try to find user in database to get full name
            $user = User::whereIn('role', ['admin', 'staff'])
                ->where('username', $this->username)
                ->first();
            
            // Use full name if user exists in database, otherwise use username
            $staffName = $user ? $user->name : $this->username;
            
            Session::put('staff_logged_in', true);
            Session::put('staff_username', $staffName);
            Session::put('staff_user_id', $user ? $user->id : null);
            Session::put('staff_role', $user ? $user->role : 'staff');
            Session::put('staff_login_time', now());
            Session::regenerate(); // Prevent session fixation
            
            $this->isLoggedIn = true;
            $this->errorMessage = '';
            $this->password = ''; // Clear password from memory
            
            RateLimiter::clear($key);
        } else {
            $this->errorMessage = 'Username atau password salah!';
            $this->password = ''; // Clear password
            RateLimiter::hit($key, 300);
        }
    }

    public function logout()
    {
        Session::forget('staff_logged_in');
        Session::flush();
        return redirect()->route('staff.login');
    }
}