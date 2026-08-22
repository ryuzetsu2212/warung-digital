<div class="min-h-screen bg-gradient-to-b from-slate-950 to-slate-900 py-8 px-4">
    <div class="max-w-4xl mx-auto">
         <!-- Header -->
         <div class="bg-slate-900 border border-white/10 rounded-2xl shadow-xl p-6 mb-6 sticky top-0 z-50">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <a href="{{ route('customer.reservations.create') }}" class="inline-block hover:opacity-80 transition-opacity">
                        <h1 class="text-3xl font-bold text-amber-400">Buat Reservasi</h1>
                        <p class="text-slate-400 mt-1">Pesan meja dan pilih menu favorit Anda</p>
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('customer.reservations') }}" class="text-amber-400 hover:text-amber-300 font-medium">
                        Lihat Reservasi Saya
                    </a>
                    <button type="button" wire:click="logout" class="bg-red-600/80 hover:bg-red-600 text-white text-sm font-medium px-3.5 py-1.5 rounded-lg border border-red-500/30 transition-all flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </button>
                </div>
            </div>
        </div>

        <!-- Staff Contact Information -->
        <div class="bg-gradient-to-r from-green-900/30 to-green-800/30 border border-green-500/30 rounded-2xl shadow-xl p-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="bg-green-500/20 rounded-full p-3">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-green-300/80 mb-1">Butuh bantuan? Hubungi staff kami</p>
                    <a href="tel:082285011556" class="text-lg font-bold text-green-400 hover:text-green-300 transition-colors">
                        082285011556 (WA)
                    </a>
                </div>
            </div>
        </div>

        <!-- Customer Profile Section -->
        <div class="bg-slate-800/50 border border-white/10 rounded-2xl shadow-xl p-6 mb-6">
            @if(!$editMode)
                <!-- View Mode -->
                <div class="flex items-start gap-4">
                    <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-full p-3 flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h2 class="text-lg font-bold text-white mb-3">Profil Tamu</h2>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-slate-300">
                                <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="font-medium">{{ auth()->user()->name }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-slate-300">
                                <svg class="w-4 h-4 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span class="font-medium">{{ auth()->user()->phone }}</span>
                            </div>
                        </div>
                        <button 
                            type="button"
                            wire:click="toggleEditMode"
                            class="mt-4 bg-amber-600/10 hover:bg-amber-600/20 text-amber-400 hover:text-amber-300 px-4 py-2 rounded-lg font-medium flex items-center gap-2 transition-all border border-amber-500/30 hover:border-amber-500/50"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Profil
                        </button>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <div class="text-xs text-slate-400 mb-1">Total Reservasi</div>
                        <div class="text-2xl font-bold text-amber-400">{{ $reservationCount }}</div>
                    </div>
                </div>
            @else
                <!-- Edit Mode -->
                <div>
                    <div class="flex items-center gap-2 mb-5">
                        <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-full p-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-white">Edit Profil</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Name Field -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                Nama <span class="text-red-400">*</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model="editName"
                                class="w-full px-4 py-3 bg-slate-950/50 border border-slate-700 text-slate-100 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('editName') border-red-500 @enderror"
                                placeholder="Masukkan nama"
                            >
                            @error('editName')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone Field -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                Nomor Telepon <span class="text-red-400">*</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model="editPhone"
                                class="w-full px-4 py-3 bg-slate-950/50 border border-slate-700 text-slate-100 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('editPhone') border-red-500 @enderror"
                                placeholder="Masukkan nomor telepon"
                            >
                            @error('editPhone')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Section -->
                        <div class="border-t border-slate-700 pt-4 mt-4">
                            <h3 class="text-sm font-semibold text-slate-300 mb-3">Ubah Password (Opsional)</h3>
                            <p class="text-xs text-slate-400 mb-4">Kosongkan jika tidak ingin mengubah password</p>
                            
                            <div class="space-y-4">
                                <!-- New Password Field -->
                                <div x-data="{ show: false }">
                                    <label class="block text-sm font-medium text-slate-300 mb-2">
                                        Password Baru
                                    </label>
                                    <div class="relative">
                                        <input 
                                            :type="show ? 'text' : 'password'"
                                            wire:model="editPassword"
                                            class="w-full px-4 py-3 pr-12 bg-slate-950/50 border border-slate-700 text-slate-100 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('editPassword') border-red-500 @enderror"
                                            placeholder="Minimal 8 karakter"
                                        >
                                        <button 
                                            type="button"
                                            @click="show = !show"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-300 transition-colors focus:outline-none"
                                        >
                                            <svg x-show="!show" class="w-5 h-5 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <svg x-show="show" x-cloak class="w-5 h-5 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                            </svg>
                                        </button>
                                    </div>
                                    @error('editPassword')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Confirm Password Field -->
                                <div x-data="{ show: false }">
                                    <label class="block text-sm font-medium text-slate-300 mb-2">
                                        Konfirmasi Password Baru
                                    </label>
                                    <div class="relative">
                                        <input 
                                            :type="show ? 'text' : 'password'"
                                            wire:model="editPassword_confirmation"
                                            class="w-full px-4 py-3 pr-12 bg-slate-950/50 border border-slate-700 text-slate-100 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('editPassword_confirmation') border-red-500 @enderror"
                                            placeholder="Ketik ulang password baru"
                                        >
                                        <button 
                                            type="button"
                                            @click="show = !show"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-300 transition-colors focus:outline-none"
                                        >
                                            <svg x-show="!show" class="w-5 h-5 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <svg x-show="show" x-cloak class="w-5 h-5 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                            </svg>
                                        </button>
                                    </div>
                                    @error('editPassword_confirmation')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 pt-4">
                            <button 
                                type="button"
                                wire:click="updateProfile"
                                class="flex-1 bg-amber-600 hover:bg-amber-500 text-white py-3 px-4 rounded-lg font-semibold transition-all flex items-center justify-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Perubahan
                            </button>
                            <button 
                                type="button"
                                wire:click="cancelEdit"
                                class="flex-1 bg-slate-700 hover:bg-slate-600 text-slate-300 py-3 px-4 rounded-lg font-semibold transition-all"
                            >
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Confirmed Reservations Section (Public) -->
        @if(count($confirmedReservations) > 0)
        <div class="bg-slate-800/50 border border-white/10 rounded-2xl shadow-xl p-6 mb-6">
            <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                Reservasi yang Sudah Dikonfirmasi
            </h2>
            <p class="text-slate-400 text-sm mb-4">Lihat meja dan jadwal yang sudah direservasi oleh tamu lain</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($confirmedReservations as $reservation)
                <div class="bg-slate-900/50 border border-green-500/30 rounded-xl p-4 hover:border-green-500/50 transition-all">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <div class="text-xs text-slate-400">Meja</div>
                            <div class="text-2xl font-bold text-amber-400">{{ $reservation->table->nomor_meja }}</div>
                        </div>
                        <div class="px-2 py-1 rounded-full text-xs font-semibold bg-green-500/20 text-green-400">
                            Confirmed
                        </div>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center gap-2 text-slate-300">
                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-300">
                            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-medium">
                                {{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }}
                                @if($reservation->reservation_end_time)
                                - {{ \Carbon\Carbon::parse($reservation->reservation_end_time)->format('H:i') }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Reservation History Section -->
        @if($reservationCount > 0)
        <div class="bg-slate-800/50 border border-white/10 rounded-2xl shadow-xl p-6 mb-6">
            <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Riwayat Reservasi Saya
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($recentReservations as $reservation)
                <div class="bg-slate-900/50 border border-slate-700 rounded-xl p-4 hover:border-amber-500/50 transition-all">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <div class="text-xs text-slate-400">Meja</div>
                            <div class="text-lg font-bold text-white">{{ $reservation->table->nomor_meja }}</div>
                        </div>
                        <div class="px-2 py-1 rounded-full text-xs font-semibold
                            @if($reservation->status === 'confirmed') bg-green-500/20 text-green-400
                            @elseif($reservation->status === 'pending') bg-yellow-500/20 text-yellow-400
                            @elseif($reservation->status === 'canceled') bg-red-500/20 text-red-400
                            @else bg-slate-500/20 text-slate-400
                            @endif">
                            {{ ucfirst($reservation->status) }}
                        </div>
                    </div>
                    <div class="space-y-1.5 text-xs">
                        <div class="flex items-center gap-2 text-slate-300">
                            <svg class="w-3.5 h-3.5 flex-shrink-0 text-slate-400" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-300">
                            <svg class="w-3.5 h-3.5 flex-shrink-0 text-slate-400" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-300">
                            <svg class="w-3.5 h-3.5 flex-shrink-0 text-slate-400" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span>{{ $reservation->number_of_guests }} Orang</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4 text-center">
                <a href="{{ route('customer.reservations') }}" class="text-amber-400 hover:text-amber-300 font-medium inline-flex items-center gap-1 text-sm">
                    Lihat Semua Reservasi
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
        @endif

        <!-- Success/Error Messages -->
        @if(session()->has('success'))
        <div class="bg-green-500/10 border border-green-500/50 rounded-2xl p-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="bg-green-500 rounded-full p-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-green-400 font-medium">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if(session()->has('error'))
        <div class="bg-red-500/10 border border-red-500/50 rounded-2xl p-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="bg-red-500 rounded-full p-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <p class="text-red-400 font-medium">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        @if(session()->has('warning'))
        <div class="bg-yellow-500/10 border border-yellow-500/50 rounded-2xl p-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="bg-yellow-500 rounded-full p-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <p class="text-yellow-400 font-medium">{{ session('warning') }}</p>
            </div>
        </div>
        @endif

        <!-- Reservation Form -->
        <form wire:submit.prevent="submit">
            <!-- Basic Information -->
            <div class="bg-slate-800/50 border border-white/10 rounded-2xl shadow-xl p-6 mb-6">
                <h2 class="text-xl font-bold text-white mb-4">Informasi Reservasi</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Table Selection -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Pilih Meja <span class="text-red-400">*</span>
                        </label>
                        <select 
                            wire:model.live="table_id"
                            class="w-full px-4 py-3 bg-slate-900 border border-slate-600 text-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('table_id') border-red-500 @enderror"
                            required
                        >
                            <option value="" class="bg-slate-900 text-slate-400">-- Pilih Meja --</option>
                            @foreach($tables as $table)
                                <option 
                                    value="{{ $table->id }}" 
                                    class="bg-slate-900 text-amber-300"
                                >
                                    Meja {{ $table->nomor_meja }}{{ $table->is_occupied ? ' (Sedang Terisi)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('table_id')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-slate-400 flex items-start gap-1.5">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Meja yang sedang terisi tetap bisa direservasi untuk waktu lain. Sistem akan memvalidasi ketersediaan waktu saat Anda submit.</span>
                        </p>
                    </div>

                    <!-- Guest Count -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Jumlah Tamu <span class="text-red-400">*</span>
                        </label>
                        <input 
                            type="number" 
                            wire:model="guest_count"
                            min="1"
                            class="w-full px-4 py-3 bg-slate-950/50 border border-slate-700 text-slate-100 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('guest_count') border-red-500 @enderror"
                            placeholder="Jumlah tamu"
                            required
                        >
                        @error('guest_count')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reservation Date -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Tanggal Reservasi <span class="text-red-400">*</span>
                        </label>
                        <input 
                            type="date" 
                            wire:model="reservation_date"
                            min="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-3 bg-slate-950/50 border border-slate-700 text-slate-100 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('reservation_date') border-red-500 @enderror"
                            required
                        >
                        @error('reservation_date')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reservation Time Start -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Waktu Mulai <span class="text-red-400">*</span>
                        </label>
                        <input 
                            type="time" 
                            wire:model="reservation_time"
                            min="07:00"
                            max="22:30"
                            class="w-full px-4 py-3 bg-slate-950/50 border border-slate-700 text-slate-100 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('reservation_time') border-red-500 @enderror"
                            required
                        >
                        @error('reservation_time')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reservation Time End -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Waktu Selesai <span class="text-red-400">*</span>
                        </label>
                        <input 
                            type="time" 
                            wire:model="reservation_end_time"
                            min="07:00"
                            max="22:30"
                            class="w-full px-4 py-3 bg-slate-950/50 border border-slate-700 text-slate-100 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('reservation_end_time') border-red-500 @enderror"
                            required
                        >
                        @error('reservation_end_time')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-slate-400 col-span-2 flex items-start gap-1.5">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Reservasi tersedia hingga jam 22:30 (10:30 malam). Jam operasional: 07:00 - 23:00</span>
                        </p>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Catatan (Opsional)
                    </label>
                    <textarea 
                        wire:model="notes"
                        rows="3"
                        class="w-full px-4 py-3 bg-slate-950/50 border border-slate-700 text-slate-100 placeholder-slate-600 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                        placeholder="Tambahkan catatan khusus untuk reservasi Anda..."
                    ></textarea>
                </div>
            </div>

            <!-- Menu Selection -->
            <div class="bg-slate-800/50 border border-white/10 rounded-2xl shadow-xl p-6 mb-6">
                <h2 class="text-xl font-bold text-white mb-4">Pilih Menu (Opsional)</h2>
                <p class="text-slate-400 mb-6">Anda dapat memilih menu sekarang atau nanti di restoran</p>

                <!-- Search & Filter -->
                <div class="mb-6">
                    <input 
                        type="text"
                        wire:model.live="search"
                        placeholder="Cari menu..."
                        class="w-full px-4 py-3 bg-slate-950/50 border border-slate-700 text-slate-100 placeholder-slate-600 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                    >
                </div>

                <!-- Product Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($products as $product)
                        <div class="border border-slate-700 bg-slate-900/50 rounded-lg p-4 hover:shadow-lg hover:border-slate-600 transition-all">
                            <!-- Product Image -->
                            <div class="aspect-square bg-slate-800 rounded-lg mb-3 flex items-center justify-center overflow-hidden">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->nama }}" class="w-full h-full object-cover" onerror="this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center text-5xl bg-gradient-to-br from-amber-500/10 to-orange-600/10\'>🍲</div>';"/>
                                @else
                                    <svg class="w-16 h-16 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <h3 class="font-semibold text-white mb-1">{{ $product->nama }}</h3>
                            <p class="text-amber-400 font-bold mb-3">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>

                            <!-- Availability -->
                            @if($product->is_available)
                                <!-- Quantity Controls -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <button 
                                            type="button"
                                            wire:click="decrementQuantity({{ $product->id }})"
                                            class="w-8 h-8 rounded-full bg-slate-700 hover:bg-slate-600 text-slate-300 flex items-center justify-center transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                            </svg>
                                        </button>
                                        
                                        <span class="w-12 text-center font-semibold text-white">
                                            {{ $selectedItems[$product->id] ?? 0 }}
                                        </span>
                                        
                                        <button 
                                            type="button"
                                            wire:click="incrementQuantity({{ $product->id }})"
                                            class="w-8 h-8 rounded-full bg-amber-600 hover:bg-amber-500 text-white flex items-center justify-center transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <span class="inline-block px-3 py-1 bg-slate-700 text-slate-400 text-sm rounded-full">
                                    Tidak Tersedia
                                </span>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-8 text-slate-500">
                            Tidak ada menu yang tersedia
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Order Summary -->
            @if(count($selectedItems) > 0)
                <div class="bg-slate-800/50 border border-white/10 rounded-2xl shadow-xl p-6 mb-6">
                    <h2 class="text-xl font-bold text-white mb-4">Ringkasan Pesanan</h2>
                    
                    <div class="space-y-3 mb-4">
                        @foreach($selectedItems as $productId => $quantity)
                            @php
                                $product = $products->firstWhere('id', $productId);
                            @endphp
                            @if($product && $quantity > 0)
                                <div class="flex items-center justify-between py-2 border-b border-slate-700">
                                    <div class="flex-1">
                                         <p class="font-medium text-white">{{ $product->nama }}</p>
                                         <p class="text-sm text-slate-400">{{ $quantity }}x Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                                    </div>
                                     <p class="font-semibold text-white">
                                         Rp {{ number_format($product->harga * $quantity, 0, ',', '.') }}
                                     </p>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="border-t-2 border-slate-600 pt-4">
                        <div class="flex items-center justify-between text-lg font-bold">
                            <span class="text-white">Total</span>
                            <span class="text-amber-400">Rp {{ number_format($this->calculateTotal(), 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Messages -->
            @if (session()->has('success'))
                <div class="bg-emerald-950/40 border border-emerald-500/30 text-emerald-300 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-950/40 border border-red-500/30 text-red-300 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Submit Button -->
            <div class="flex gap-4">
                <a 
                    href="/"
                    class="flex-1 bg-slate-800 text-slate-300 py-4 px-6 rounded-lg font-semibold border border-slate-700 hover:bg-slate-700 transition-all text-center"
                >
                    Batal
                </a>
                <button 
                    type="submit"
                    wire:loading.attr="disabled"
                    class="flex-1 bg-amber-600 text-white py-4 px-6 rounded-lg font-semibold hover:bg-amber-500 focus:ring-4 focus:ring-amber-500/20 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span wire:loading.remove>Buat Reservasi</span>
                    <span wire:loading>Memproses...</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Scroll to Top Button -->
    <button 
        id="scrollToTopBtn"
        onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
        class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 bg-amber-600 hover:bg-amber-500 active:bg-amber-700 text-white p-3 sm:p-4 rounded-full shadow-2xl transition-all duration-300 opacity-0 invisible z-50 touch-manipulation"
        style="will-change: opacity, visibility;"
        aria-label="Scroll to top"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
        </svg>
    </button>

    <script>
        // Show/hide scroll to top button
        function toggleScrollBtn() {
            const scrollBtn = document.getElementById('scrollToTopBtn');
            if (!scrollBtn) return;
            const scrollY = window.pageYOffset || window.scrollY || document.documentElement.scrollTop;
            if (scrollY > 300) {
                scrollBtn.classList.remove('opacity-0', 'invisible');
                scrollBtn.classList.add('opacity-100', 'visible');
            } else {
                scrollBtn.classList.add('opacity-0', 'invisible');
                scrollBtn.classList.remove('opacity-100', 'visible');
            }
        }

        // Listen for scroll and touchmove events
        window.addEventListener('scroll', toggleScrollBtn, { passive: true });
        window.addEventListener('touchmove', toggleScrollBtn, { passive: true });
        // Initial check
        toggleScrollBtn();

        // Re-run after Livewire updates (DOM morphs)
        document.addEventListener('livewire:update', function() {
            toggleScrollBtn();
        });
    </script>
</div>
