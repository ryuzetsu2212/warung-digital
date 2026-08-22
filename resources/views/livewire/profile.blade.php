<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 py-8 px-4">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <button 
                wire:click="$dispatch('navigate-back')"
                onclick="window.history.back()"
                class="inline-flex items-center text-indigo-400 hover:text-indigo-300 mb-4 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </button>
            <h1 class="text-3xl font-black text-white">Profil Saya</h1>
            <p class="text-slate-400 mt-1">Kelola informasi profil dan keamanan akun Anda</p>
        </div>

        <!-- Profile Information Card -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-3xl shadow-xl p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-white">Informasi Profil</h2>
                <span class="px-3 py-1 text-xs font-bold rounded-xl
                    {{ $user->role === 'admin' ? 'bg-purple-600/20 text-purple-400 border border-purple-500/30' : '' }}
                    {{ $user->role === 'staff' ? 'bg-blue-600/20 text-blue-400 border border-blue-500/30' : '' }}
                    {{ $user->role === 'customer' ? 'bg-emerald-600/20 text-emerald-400 border border-emerald-500/30' : '' }}">
                    {{ ucfirst($user->role) }}
                </span>
            </div>

            @if (session()->has('profile_message'))
                <div class="mb-4 p-4 bg-emerald-500/10 border border-emerald-500/40 text-emerald-300 rounded-2xl flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('profile_message') }}
                </div>
            @endif

            <form wire:submit.prevent="updateProfile">
                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-bold text-slate-300 mb-2">
                            Nama Lengkap
                        </label>
                        <input 
                            type="text" 
                            id="name"
                            wire:model="name"
                            class="w-full px-4 py-3 bg-slate-950 border border-slate-700 text-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition"
                            placeholder="Masukkan nama lengkap">
                        @error('name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-bold text-slate-300 mb-2">
                            Nomor Telepon
                        </label>
                        <input 
                            type="tel" 
                            id="phone"
                            wire:model="phone"
                            class="w-full px-4 py-3 bg-slate-950 border border-slate-700 text-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition"
                            placeholder="08xx xxxx xxxx">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-300 mb-2">
                            Email (Opsional)
                        </label>
                        <input 
                            type="email" 
                            id="email"
                            wire:model="email"
                            class="w-full px-4 py-3 bg-slate-950 border border-slate-700 text-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition"
                            placeholder="nama@email.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <button 
                        type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 rounded-xl transition shadow-lg flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Password Security Card -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-3xl shadow-xl p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-bold text-white">Keamanan Password</h2>
                    <p class="text-sm text-slate-400 mt-1">Ubah password untuk meningkatkan keamanan akun</p>
                </div>
                <button 
                    type="button"
                    wire:click="togglePasswordSection"
                    class="text-indigo-400 hover:text-indigo-300 font-bold text-sm transition">
                    {{ $showPasswordSection ? 'Tutup' : 'Ubah Password' }}
                </button>
            </div>

            @if (session()->has('password_message'))
                <div class="mb-4 p-4 bg-emerald-500/10 border border-emerald-500/40 text-emerald-300 rounded-2xl flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('password_message') }}
                </div>
            @endif

            @if($showPasswordSection)
                <form wire:submit.prevent="updatePassword" class="mt-4">
                    <div class="space-y-4">
                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="block text-sm font-bold text-slate-300 mb-2">
                                Password Saat Ini
                            </label>
                            <input 
                                type="password" 
                                id="current_password"
                                wire:model="current_password"
                                class="w-full px-4 py-3 bg-slate-950 border border-slate-700 text-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition"
                                placeholder="Masukkan password saat ini">
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="new_password" class="block text-sm font-bold text-slate-300 mb-2">
                                Password Baru
                            </label>
                            <input 
                                type="password" 
                                id="new_password"
                                wire:model="new_password"
                                class="w-full px-4 py-3 bg-slate-950 border border-slate-700 text-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition"
                                placeholder="Minimal 8 karakter">
                            @error('new_password')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm New Password -->
                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-bold text-slate-300 mb-2">
                                Konfirmasi Password Baru
                            </label>
                            <input 
                                type="password" 
                                id="new_password_confirmation"
                                wire:model="new_password_confirmation"
                                class="w-full px-4 py-3 bg-slate-950 border border-slate-700 text-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition"
                                placeholder="Masukkan ulang password baru">
                            @error('new_password_confirmation')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <button 
                            type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 rounded-xl transition shadow-lg flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Update Password
                        </button>
                    </div>
                </form>
            @endif
        </div>

        <!-- Account Info -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-3xl shadow-xl p-6">
            <h3 class="text-lg font-bold text-white mb-3">Informasi Akun</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between items-center py-2 border-b border-slate-800">
                    <span class="text-slate-400">Role:</span>
                    <span class="font-bold text-white">{{ ucfirst($user->role) }}</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-slate-400">Bergabung sejak:</span>
                    <span class="font-bold text-white">{{ $user->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>