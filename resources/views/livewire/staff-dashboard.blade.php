<div wire:poll.3s class="max-w-7xl mx-auto px-4 py-8" x-data="{
    isLoggedIn: @entangle('isLoggedIn'),
    tab: @entangle('tab'),
    showAlert: false,
    alertData: {}
}" x-init="
    Livewire.on('session-expired', () => { window.location.reload(); });
    Livewire.on('payment-requested', (event) => {
        alertData = event[0] || event;
        showAlert = true;
        setTimeout(() => { showAlert = false; }, 6000);
    });
">
    <!-- Live Pop-up Notification for Payment Request -->
    <div x-show="showAlert" style="display: none;" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed top-6 right-6 z-50 max-w-md w-full bg-slate-900 border-2 border-amber-500 rounded-3xl p-5 shadow-2xl text-white">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-amber-500/20 border border-amber-500/40 rounded-2xl flex items-center justify-center text-2xl shrink-0">
                🔔
            </div>
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <h4 class="font-bold text-amber-400 text-sm">Permintaan Pembayaran Baru!</h4>
                    <button @click="showAlert = false" class="text-slate-400 hover:text-white font-bold text-lg">×</button>
                </div>
                <p class="text-xs text-slate-300 mt-1">Meja <strong class="text-white" x-text="alertData.table_no"></strong> meminta konfirmasi pembayaran untuk Pesanan <strong class="text-indigo-400" x-text="'#' + alertData.order_id"></strong>.</p>
                <div class="mt-3 flex gap-2">
                    <button @click="tab = 'riwayat'; showAlert = false;" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold px-3 py-1.5 rounded-xl text-xs transition">
                        Lihat Riwayat & Konfirmasi
                    </button>
                </div>
            </div>
        </div>
    </div>
    @if(!$isLoggedIn)
        <!-- Form Login Staff - Modern & Clean UI -->
        <div class="min-h-[80vh] flex items-center justify-center px-4">
            <div class="max-w-md w-full bg-slate-900/90 backdrop-blur-xl border border-slate-800 rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden">
                <!-- Glowing Accent Background -->
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="text-center mb-8 relative z-10">
                    <div class="w-20 h-20 bg-indigo-600/10 border border-indigo-500/30 rounded-3xl mx-auto flex items-center justify-center text-4xl mb-4 shadow-inner">
                        👨‍🍳
                    </div>
                    <h1 class="text-2xl font-black text-white tracking-wide">Login Staff & Dapur</h1>
                    <p class="text-slate-400 text-xs sm:text-sm mt-1">Warung Digital Management System</p>
                </div>

                @if($errorMessage)
                    <div class="mb-6 bg-red-500/10 border border-red-500/40 text-red-300 px-4 py-3 rounded-2xl text-xs sm:text-sm text-center font-medium animate-shake relative z-10">
                        {{ $errorMessage }}
                    </div>
                @endif

                <form wire:submit.prevent="login" class="space-y-5 relative z-10">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Username</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm">👤</span>
                            <input type="text" wire:model="username" required
                                   class="w-full bg-slate-950/60 border border-slate-800 rounded-2xl pl-11 pr-4 py-3.5 text-slate-100 placeholder-slate-600 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition"
                                   placeholder="Masukkan username...">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Password</label>
                        <div class="relative" x-data="{ show: false }">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm">🔒</span>
                            <input :type="show ? 'text' : 'password'" wire:model="password" required
                                   class="w-full bg-slate-950/60 border border-slate-800 rounded-2xl pl-11 pr-12 py-3.5 text-slate-100 placeholder-slate-600 text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition"
                                   placeholder="Masukkan password...">
                            <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 focus:outline-none p-1 transition">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="show" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.07 10.07 0 014.136-5.4M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold py-4 px-4 rounded-2xl transition shadow-lg shadow-indigo-600/30 text-sm tracking-wide mt-2">
                        Masuk ke Dashboard
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-slate-800 text-center relative z-10">
                    <a href="{{ url('/') }}" class="text-xs text-slate-400 hover:text-amber-400 font-medium transition inline-flex items-center gap-1.5">
                        ← Kembali ke Beranda Pelanggan
                    </a>
                </div>
            </div>
        </div>
    @else
<!-- Navigation Bar -->
<div class="sticky top-0 z-40 bg-slate-900/95 backdrop-blur border-b border-slate-800 shadow-lg" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo & Title (Clickable) -->
            <a href="{{ route('staff.dashboard') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity cursor-pointer">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-violet-600 rounded-xl flex items-center justify-center text-white font-bold">
                    👨‍🍳
                </div>
                <h1 class="text-lg font-black text-white">{{ $staffName }}</h1>
            </a>
            
            <!-- Desktop Menu - Hidden on mobile -->
            <div class="hidden md:flex items-center gap-2">
                <button @click="tab = 'antrean'" :class="tab === 'antrean' ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'" class="px-4 py-2 rounded-lg font-semibold text-sm transition whitespace-nowrap">
                    🍳 Antrean Dapur
                </button>
                <button @click="tab = 'menu'" :class="tab === 'menu' ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'" class="px-4 py-2 rounded-lg font-semibold text-sm transition whitespace-nowrap">
                    📦 Menu & Stok
                </button>
                <button @click="tab = 'qr'" :class="tab === 'qr' ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'" class="px-4 py-2 rounded-lg font-semibold text-sm transition whitespace-nowrap">
                    📱 QR Meja
                </button>
                <button @click="tab = 'riwayat'" :class="tab === 'riwayat' ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'" class="px-4 py-2 rounded-lg font-semibold text-sm transition whitespace-nowrap">
                    📋 Riwayat
                </button>
                <button @click="tab = 'reservasi'" :class="tab === 'reservasi' ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'" class="px-4 py-2 rounded-lg font-semibold text-sm transition whitespace-nowrap">
                    🏷️ Reservasi
                </button>
                <a href="{{ route('staff.profile') }}" class="ml-2 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white px-4 py-2 rounded-lg font-semibold text-sm transition whitespace-nowrap flex items-center gap-1.5">
                    👤 Profil
                </a>
                <button wire:click="logout" class="bg-red-600/20 hover:bg-red-600/40 border border-red-600/40 text-red-400 px-4 py-2 rounded-lg font-semibold text-sm transition whitespace-nowrap">
                    Keluar
                </button>
            </div>

            <!-- Mobile Menu Button - Visible only on mobile -->
            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="bg-slate-800 hover:bg-slate-700 text-white p-2.5 rounded-lg transition">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileMenuOpen" style="display: none;" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Dropdown Menu -->
        <div x-show="mobileMenuOpen" 
             @click.away="mobileMenuOpen = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             style="display: none;"
             class="md:hidden mt-4 space-y-2 pb-2">
            <button @click="tab = 'antrean'; mobileMenuOpen = false" 
                    :class="tab === 'antrean' ? 'bg-indigo-600 text-white shadow-lg' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'" 
                    class="w-full text-left px-4 py-3.5 rounded-xl font-semibold text-sm transition">
                🍳 Antrean Dapur
            </button>
            <button @click="tab = 'menu'; mobileMenuOpen = false" 
                    :class="tab === 'menu' ? 'bg-indigo-600 text-white shadow-lg' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'" 
                    class="w-full text-left px-4 py-3.5 rounded-xl font-semibold text-sm transition">
                📦 Menu & Stok
            </button>
            <button @click="tab = 'qr'; mobileMenuOpen = false" 
                    :class="tab === 'qr' ? 'bg-indigo-600 text-white shadow-lg' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'" 
                    class="w-full text-left px-4 py-3.5 rounded-xl font-semibold text-sm transition">
                📱 QR Code Meja
            </button>
            <button @click="tab = 'riwayat'; mobileMenuOpen = false" 
                    :class="tab === 'riwayat' ? 'bg-indigo-600 text-white shadow-lg' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'" 
                    class="w-full text-left px-4 py-3.5 rounded-xl font-semibold text-sm transition">
                📋 Riwayat Pesanan
            </button>
            <button @click="tab = 'reservasi'; mobileMenuOpen = false" 
                    :class="tab === 'reservasi' ? 'bg-indigo-600 text-white shadow-lg' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'" 
                    class="w-full text-left px-4 py-3.5 rounded-xl font-semibold text-sm transition">
                🏷️ Manajemen Reservasi
            </button>
            
            <!-- Profile & Logout Button in Mobile Menu -->
            <div class="pt-2 mt-2 border-t border-slate-700 space-y-2">
                <a href="{{ route('staff.profile') }}" 
                   class="w-full block text-left bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white px-4 py-3.5 rounded-xl font-semibold text-sm transition">
                    👤 Profil
                </a>
                <button wire:click="logout" 
                        class="w-full text-left bg-red-600/20 hover:bg-red-600/30 border border-red-600/40 text-red-400 px-4 py-3.5 rounded-xl font-semibold text-sm transition">
                    🚪 Keluar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- TAB 1: ANTREAN DAPUR -->
<div x-show="tab === 'antrean'">
    @livewire('staff.kitchen-queue')
</div>

<!-- TAB 2: MANAJEMEN MENU & STOK -->
<div x-show="tab === 'menu'" style="display: none;">
    @livewire('staff.menu-stock')
</div>

<!-- TAB 3: QR CODE MEJA -->
<div x-show="tab === 'qr'" style="display: none;">
    @livewire('staff.table-qr')
</div>

<!-- TAB 4: RIWAYAT PESANAN -->
<div x-show="tab === 'riwayat'" style="display: none;">
    @livewire('staff.order-history')
</div>

<!-- TAB 5: MANAJEMEN RESERVASI -->
<div x-show="tab === 'reservasi'" style="display: none;">
    @livewire('staff.reservation-management')
</div>
    @endif
</div>