<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;

class Profile extends Component
{
    public $name;
    public $phone;
    public $email;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    
    public $showPasswordSection = false;

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users')->ignore(Auth::id())
            ],
        ];
    }

    protected function passwordRules()
    {
        return [
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function mount()
    {
        // Get user from session or Auth
        $userId = session('staff_user_id') ?? Auth::id();
        
        if (!$userId) {
            return redirect()->route('staff.login');
        }
        
        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('staff.login');
        }
        
        $this->name = $user->name;
        $this->phone = $user->phone;
        $this->email = $user->email;
    }

    public function updateProfile()
    {
        $this->validate();

        // Get user from session or Auth
        $userId = session('staff_user_id') ?? Auth::id();
        
        if (!$userId) {
            session()->flash('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
            return redirect()->route('staff.login');
        }
        
        $user = User::find($userId);
        
        if (!$user) {
            session()->flash('error', 'User tidak ditemukan.');
            return redirect()->route('staff.login');
        }
        
        $user->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
        ]);

        session()->flash('profile_message', 'Profil berhasil diperbarui!');
    }

    public function updatePassword()
    {
        $this->validate($this->passwordRules());

        // Get user from session or Auth
        $userId = session('staff_user_id') ?? Auth::id();
        
        if (!$userId) {
            session()->flash('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
            return redirect()->route('staff.login');
        }
        
        $user = User::find($userId);
        
        if (!$user) {
            session()->flash('error', 'User tidak ditemukan.');
            return redirect()->route('staff.login');
        }

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Password saat ini tidak sesuai.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        // Reset password fields
        $this->current_password = '';
        $this->new_password = '';
        $this->new_password_confirmation = '';
        $this->showPasswordSection = false;

        session()->flash('password_message', 'Password berhasil diubah!');
    }

    public function togglePasswordSection()
    {
        $this->showPasswordSection = !$this->showPasswordSection;
        
        // Reset password fields when closing
        if (!$this->showPasswordSection) {
            $this->current_password = '';
            $this->new_password = '';
            $this->new_password_confirmation = '';
            $this->resetErrorBag(['current_password', 'new_password', 'new_password_confirmation']);
        }
    }

    public function render()
    {
        // Get user from session or Auth
        $userId = session('staff_user_id') ?? Auth::id();
        $user = User::find($userId);
        
        return view('livewire.profile', [
            'user' => $user
        ]);
    }
}