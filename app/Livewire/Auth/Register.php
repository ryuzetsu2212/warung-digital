<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Register extends Component
{
    public $name;
    public $email;
    public $phone;
    public $password;
    public $password_confirmation;
    public $errorMessage = '';
    public $successMessage = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|unique:users,email',
        'phone' => 'required|numeric|digits_between:10,15|unique:users,phone',
        'password' => 'required|string|min:8|confirmed',
    ];

    protected $messages = [
        'name.required' => 'Nama lengkap wajib diisi',
        'email.email' => 'Format email tidak valid',
        'email.unique' => 'Email sudah terdaftar',
        'phone.required' => 'Nomor telepon wajib diisi',
        'phone.numeric' => 'Nomor telepon hanya boleh berisi angka',
        'phone.digits_between' => 'Nomor telepon harus 10-15 digit',
        'phone.unique' => 'Nomor telepon sudah terdaftar',
        'password.required' => 'Password wajib diisi',
        'password.min' => 'Password minimal 8 karakter',
        'password.confirmed' => 'Konfirmasi password tidak cocok',
    ];

    public function register()
    {
        $this->errorMessage = '';
        $this->successMessage = '';

        try {
            $this->validate();

            // Create user account (role: customer by default)
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'password' => Hash::make($this->password),
                'role' => 'customer', // Default role is customer
            ]);

            // Auto login after registration
            Auth::login($user);

            // Redirect to home or reservation page
            return redirect()->route('customer.welcome')
                ->with('success', 'Pendaftaran berhasil! Selamat datang, ' . $user->name);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->errorMessage = 'Terjadi kesalahan validasi. Periksa kembali form Anda.';
        } catch (\Exception $e) {
            $this->errorMessage = 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.';
        }
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('components.layouts.app');
    }
}