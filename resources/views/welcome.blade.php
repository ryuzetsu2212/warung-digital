<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Warung Digital - Pemesanan & Reservasi Digital</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍽️</text></svg>">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" crossorigin="anonymous" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 flex flex-col justify-between selection:bg-amber-500 selection:text-slate-950 relative overflow-x-hidden font-sans antialiased">

    {{-- Enhanced Ambient Background Glow --}}
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute -top-32 -left-32 w-[500px] h-[500px] bg-amber-500/15 rounded-full blur-[140px] animate-pulse"></div>
        <div class="absolute top-1/3 -right-32 w-[500px] h-[500px] bg-orange-600/15 rounded-full blur-[140px] animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute -bottom-32 left-1/4 w-[500px] h-[500px] bg-amber-500/15 rounded-full blur-[140px] animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    {{-- Header / Navbar --}}
    <header class="relative z-10 w-full max-w-5xl mx-auto px-4 sm:px-6 py-6" x-data="{ mobileMenuOpen: false }">
        <div class="flex items-center justify-between">
            <a href="{{ route('customer.welcome') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity cursor-pointer">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-400 flex items-center justify-center shadow-lg shadow-amber-500/20 text-2xl">
                    🍽️
                </div>
                 <div>
                     <span class="font-extrabold text-xl text-white tracking-tight">Warung <span class="text-amber-500">Digital</span></span>
                     @php
                         $currentHour = now()->hour;
                         $autoIsOpen = ($currentHour >= 7 && $currentHour < 17) || ($currentHour >= 19 && $currentHour < 23);
                         $manualOverride = \App\Models\Setting::getValue('admin_manual_override', false);
                         if ($manualOverride === 'closed') {
                             $isOpen = false;
                         } elseif ($manualOverride === true || $manualOverride === '1' || $manualOverride === 'true') {
                             $isOpen = true;
                         } else {
                             $isOpen = $autoIsOpen;
                         }
                     @endphp
                     @if($isOpen)
                         <span class="hidden sm:inline-block ml-2 text-xs font-semibold px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                             Buka
                         </span>
                     @else
                         <span class="hidden sm:inline-block ml-2 text-xs font-semibold px-2 py-0.5 rounded-full bg-red-500/10 text-red-400 border border-red-500/20">
                             Tutup
                         </span>
                     @endif
                 </div>
            </a>

            {{-- Desktop Menu --}}
            <div class="hidden sm:flex items-center gap-3">
                @auth
                    <a href="{{ route('customer.reservations.create') }}" class="px-4 py-2 text-sm font-semibold rounded-xl bg-white/5 border border-white/10 text-slate-300 hover:text-amber-400 hover:border-amber-500/30 shadow-sm transition-all">
                        Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-2 text-sm font-semibold rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 transition-all">
                            Keluar
                        </button>
                    </form>
                @endauth
            </div>

            {{-- Mobile Menu Button --}}
            @auth
            <div class="sm:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2.5 rounded-xl bg-white/5 border border-white/10 text-slate-300 hover:text-amber-400 hover:border-amber-500/30 transition-all">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileMenuOpen" style="display: none;" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            @endauth
        </div>

        {{-- Mobile Dropdown Menu --}}
        @auth
        <div x-show="mobileMenuOpen" 
             @click.away="mobileMenuOpen = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             style="display: none;"
             class="sm:hidden mt-4 space-y-2 pb-2">
            <a href="{{ route('customer.reservations.create') }}" 
               class="block w-full text-left px-4 py-3.5 rounded-xl bg-white/5 border border-white/10 text-slate-300 hover:text-amber-400 hover:border-amber-500/30 font-semibold text-sm transition-all">
                📊 Dashboard
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="w-full text-left px-4 py-3.5 rounded-xl bg-red-600/10 border border-red-600/30 text-red-400 hover:bg-red-600/20 font-semibold text-sm transition-all">
                    🚪 Keluar
                </button>
            </form>
        </div>
        @endauth
    </header>

    {{-- Main Content --}}
    <main class="relative z-10 w-full max-w-4xl mx-auto px-4 sm:px-6 py-6 md:py-12 flex-1 flex flex-col justify-center">

        {{-- Enhanced Hero Section --}}
        <div class="text-center max-w-2xl mx-auto mb-10 md:mb-14">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-r from-amber-500/15 to-orange-500/15 border border-amber-500/30 text-amber-400 text-xs font-semibold tracking-wide uppercase mb-8 shadow-lg shadow-amber-500/10 backdrop-blur-sm">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
                </span>
                Pemesanan Digital via QR Code
            </div>

            <h1 class="text-5xl md:text-7xl font-black tracking-tight text-white mb-6 leading-[1.1]">
                Makan Enak,<br/>
                <span class="bg-gradient-to-r from-amber-400 via-amber-500 to-orange-500 bg-clip-text text-transparent">Pesan Mudah.</span>
            </h1>

            <p class="text-slate-300 text-lg md:text-xl leading-relaxed max-w-xl mx-auto font-medium">
                Scan QR Code di meja makan, pilih menu favorit, dan nikmati hidangan lezat tanpa perlu antre panjang.
            </p>
        </div>

        {{-- Cards Container dengan Grid yang Sama --}}
        <div class="max-w-3xl mx-auto space-y-6 mb-10">
            {{-- Login untuk Reservasi --}}
            <a href="{{ route('login') }}"
               class="group relative block bg-gradient-to-br from-slate-900/80 to-slate-900/40 hover:from-slate-900 hover:to-slate-900/80 border border-white/10 hover:border-amber-500/50 rounded-3xl p-6 sm:p-7 transition-all duration-300 overflow-hidden shadow-2xl hover:shadow-amber-500/20 hover:-translate-y-2 backdrop-blur-xl">
                <div class="absolute -right-12 -bottom-12 w-48 h-48 bg-gradient-to-br from-orange-500/20 to-amber-500/20 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="absolute -left-8 -top-8 w-32 h-32 bg-amber-500/10 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-700"></div>

                <div class="relative z-10 flex flex-col sm:flex-row items-center gap-5 sm:gap-7">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500/10 to-orange-500/10 text-amber-400 group-hover:from-amber-500 group-hover:to-orange-500 group-hover:text-slate-950 flex-shrink-0 flex items-center justify-center transition-all duration-300 shadow-lg shadow-amber-500/10 border border-amber-500/20 group-hover:border-amber-500 group-hover:scale-110">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>

                    <div class="text-center sm:text-left flex-1">
                        <h3 class="text-lg font-black text-white mb-1 group-hover:text-amber-400 transition-colors">
                            Masuk Akun
                        </h3>
                        <p class="text-sm sm:text-base text-slate-300 font-medium leading-relaxed">
                            Login untuk melakukan reservasi dan kelola pesanan Anda.
                        </p>
                    </div>

                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-white/5 to-white/10 text-slate-300 group-hover:from-amber-500 group-hover:to-orange-500 group-hover:text-slate-950 flex-shrink-0 flex items-center justify-center transition-all duration-300 border border-white/10 group-hover:border-amber-500 group-hover:scale-110 shadow-lg">
                        <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </div>
                </div>
            </a>

            {{-- Enhanced Dine-in QR Guide Banner --}}
            <div class="bg-gradient-to-br from-slate-900/60 to-slate-900/30 border border-amber-500/30 rounded-3xl p-6 sm:p-7 shadow-xl backdrop-blur-xl hover:border-amber-500/40 transition-all duration-300">
                <div class="flex flex-col sm:flex-row items-center gap-5 sm:gap-7">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 text-slate-950 flex-shrink-0 flex items-center justify-center shadow-xl shadow-amber-500/30 font-bold hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                    </div>
                    <div class="text-center sm:text-left flex-1">
                        <h4 class="text-lg font-black text-white mb-1">Sudah berada di warung?</h4>
                        <p class="text-sm sm:text-base text-slate-300 font-medium leading-relaxed">
                            Arahkan kamera HP ke QR Code pada meja Anda untuk langsung memesan menu tanpa unduh aplikasi.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </main>

    {{-- Footer --}}
    <footer class="relative z-10 w-full max-w-5xl mx-auto px-4 sm:px-6 py-6 text-center text-xs text-slate-500 border-t border-white/5">
        <div class="flex items-center justify-center gap-4 mb-3">
            <p>&copy; {{ date('Y') }} Warung Digital. Semua hak dilindungi.</p>
            <a href="{{ route('staff.login') }}" class="text-slate-500 hover:text-slate-300 transition-colors opacity-60 hover:opacity-100" title="Admin">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </a>
        </div>
    </footer>

</body>
</html>