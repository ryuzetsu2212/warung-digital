<div wire:poll.3s x-init="Livewire.on('session-expired', () => { window.location.reload(); })" class="min-h-screen bg-gradient-to-b from-slate-950 to-slate-900">
    <!-- Navbar -->
    <header class="bg-slate-950/80 border-b border-white/5 sticky top-0 z-50 backdrop-blur-xl shadow-xl shadow-black/30">
        <div class="max-w-7xl mx-auto px-4 py-3 flex flex-wrap justify-between items-center gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-tr from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30 flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-base font-black text-white leading-tight tracking-tight">Warung Digital</h1>
                    <p class="text-xs text-slate-500 leading-tight">Meja <span class="font-extrabold text-amber-400">{{ $table->nomor_meja }}</span></p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @php
                    $currentHour = now()->hour;
                    $isOpen = ($currentHour >= 7 && $currentHour < 17) || ($currentHour >= 19 && $currentHour < 23);
                @endphp
                <div class="flex items-center gap-2 bg-white/5 border border-white/10 px-3 py-1.5 rounded-xl text-xs">
                    <span class="w-1.5 h-1.5 rounded-full {{ $isOpen ? 'bg-emerald-500 animate-pulse' : 'bg-red-500' }}"></span>
                    <span class="{{ $isOpen ? 'text-emerald-400' : 'text-red-400' }} font-bold">{{ $isOpen ? 'Buka' : 'Tutup' }}</span>
                    <span class="text-slate-600">·</span>
                    <span class="text-slate-400">07:00–17:00 & 19:00–23:00</span>
                </div>
                @if(!empty($cart))
                    <div class="lg:hidden flex items-center gap-1.5 bg-amber-500/15 border border-amber-500/30 text-amber-400 px-3 py-1.5 rounded-xl text-xs font-bold">
                        <span>🛒</span>
                        <span>{{ collect($cart)->sum('qty') }} item</span>
                    </div>
                @endif
            </div>
        </div>
    </header>

    @if($successMessage)
        <div wire:key="toast-{{ $toastKey }}" x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95"
             class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 bg-emerald-500 text-white px-6 py-3.5 rounded-2xl shadow-2xl shadow-emerald-500/30 border border-emerald-400/30 flex items-center gap-3 text-sm font-bold whitespace-nowrap">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ $successMessage }}</span>
        </div>
    @endif

    <main class="max-w-7xl mx-auto px-4 py-6">
        @if($serviceClosed)
            <div class="bg-slate-900/60 border border-amber-500/30 rounded-3xl p-10 text-center shadow-2xl">
                <div class="text-6xl mb-4">🌙</div>
                <h2 class="text-2xl font-black text-amber-400 mb-3">Warung Sedang Tutup</h2>
                <p class="text-slate-300 text-sm max-w-md mx-auto leading-relaxed mb-6">
                    Mohon maaf, saat ini warung sedang tutup. Silakan datang kembali pada jam operasional kami:
                </p>
                
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 max-w-sm mx-auto mb-8">
                    <h3 class="text-amber-400 font-bold text-sm mb-4">⏰ Jam Operasional</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-400">Shift Siang</span>
                            <span class="text-white font-bold">07:00 - 17:00 WIB</span>
                        </div>
                        <div class="h-px bg-white/10"></div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-400">Shift Malam</span>
                            <span class="text-white font-bold">19:00 - 23:00 WIB</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('customer.welcome') }}" 
                   class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-8 py-3.5 rounded-xl transition-all shadow-lg shadow-amber-500/30 hover:shadow-xl hover:shadow-amber-500/40 hover:scale-105">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        @elseif($sessionExpired)
            <div class="bg-red-950/40 border border-red-500/30 rounded-3xl p-10 text-center shadow-2xl mb-6">
                <div class="text-6xl mb-4">🚫</div>
                <h2 class="text-xl font-black text-red-400 mb-2">Sesi Meja Berakhir</h2>
                <p class="text-red-300/70 text-sm max-w-md mx-auto leading-relaxed">Sesi untuk meja ini telah berakhir atau sedang digunakan pengunjung lain. Hubungi pelayan untuk bantuan.</p>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-6 {{ $sessionExpired || $serviceClosed ? 'opacity-40 pointer-events-none' : '' }}">
            <!-- Left: Menu -->
            <div class="flex-1 min-w-0 space-y-5">
                <!-- Search & Filter -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 backdrop-blur-sm space-y-3">
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" wire:model.live.debounce.300ms="search"
                               placeholder="Cari menu..."
                               class="w-full bg-black/30 border border-white/10 rounded-xl pl-11 pr-10 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all">
                        @if(!empty($search))
                            <button wire:click="$set('search', '')" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white w-6 h-6 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-xs transition">✕</button>
                        @endif
                    </div>

                    <div class="flex gap-2">
                        <button wire:click="$set('selectedCategory', 'all')"
                                class="flex-1 py-2.5 rounded-xl text-xs font-bold transition-all {{ $selectedCategory === 'all' ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/30' : 'bg-white/5 text-slate-400 hover:bg-white/10 hover:text-white border border-white/10' }}">
                            Semua
                        </button>
                        <button wire:click="$set('selectedCategory', 'makanan')"
                                class="flex-1 py-2.5 rounded-xl text-xs font-bold transition-all {{ $selectedCategory === 'makanan' ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/30' : 'bg-white/5 text-slate-400 hover:bg-white/10 hover:text-white border border-white/10' }}">
                            🍲 Makanan
                        </button>
                        <button wire:click="$set('selectedCategory', 'minuman')"
                                class="flex-1 py-2.5 rounded-xl text-xs font-bold transition-all {{ $selectedCategory === 'minuman' ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/30' : 'bg-white/5 text-slate-400 hover:bg-white/10 hover:text-white border border-white/10' }}">
                            🥤 Minuman
                        </button>
                    </div>
                </div>

                @if(session()->has('error'))
                    <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                        <span>⚠️</span> {{ session('error') }}
                    </div>
                @endif

                @if($allProducts->isEmpty())
                    <div class="py-20 text-center text-slate-500">
                        <p class="text-5xl mb-4">🍽️</p>
                        <p class="text-base font-semibold text-slate-400">Menu tidak ditemukan</p>
                        <p class="text-xs text-slate-600 mt-1">Coba kata kunci atau kategori lain</p>
                    </div>
                @else
                    @if($selectedCategory === 'all' || $selectedCategory === 'makanan')
                        @if($makanan->isNotEmpty())
                            <div>
                                <div class="flex items-center gap-2 mb-4">
                                    <span class="text-xl">🍲</span>
                                    <h2 class="text-base font-black text-white uppercase tracking-wider">Makanan</h2>
                                    <span class="text-xs text-slate-500 font-medium bg-white/5 px-2 py-0.5 rounded-full">{{ $makanan->count() }}</span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                                    @foreach($makanan as $prod)
                                        <div class="bg-white/5 border border-white/10 rounded-2xl overflow-hidden hover:border-amber-500/40 hover:bg-white/8 transition-all duration-300 group flex flex-col">
                                            <div class="relative w-full aspect-[4/3] bg-slate-900 overflow-hidden flex-shrink-0">
                                                <img src="{{ $prod->image_url }}" alt="{{ $prod->nama }}"
                                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                                     onerror="this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center text-5xl bg-gradient-to-br from-amber-500/10 to-orange-600/10\'>🍲</div>';"
                                                >
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                                <div class="absolute top-2 right-2 flex items-center gap-1 bg-black/60 backdrop-blur-sm border border-white/10 px-2 py-0.5 rounded-full text-[10px] font-bold text-amber-400">
                                                    ⭐ {{ $prod->formatted_rating }}
                                                </div>
                                            </div>
                                            <div class="p-3.5 flex flex-col gap-2.5 flex-1">
                                                <div class="flex-1">
                                                    <h3 class="font-bold text-white text-sm line-clamp-2 leading-snug">{{ $prod->nama }}</h3>
                                                </div>
                                                <div class="flex items-center justify-between">
                                                    <p class="text-amber-400 font-extrabold text-sm">Rp {{ number_format($prod->harga, 0, ',', '.') }}</p>
                                                </div>
                                                <button wire:click="addToCart({{ $prod->id }})"
                                                        class="w-full bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-400 hover:to-orange-500 text-white font-bold py-2.5 rounded-xl transition-all shadow-lg shadow-amber-500/20 text-xs flex items-center justify-center gap-1.5 active:scale-95">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                    Tambah
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif

                    @if($selectedCategory === 'all' || $selectedCategory === 'minuman')
                        @if($minuman->isNotEmpty())
                            <div>
                                <div class="flex items-center gap-2 mb-4">
                                    <span class="text-xl">🥤</span>
                                    <h2 class="text-base font-black text-white uppercase tracking-wider">Minuman</h2>
                                    <span class="text-xs text-slate-500 font-medium bg-white/5 px-2 py-0.5 rounded-full">{{ $minuman->count() }}</span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                                    @foreach($minuman as $prod)
                                        <div class="bg-white/5 border border-white/10 rounded-2xl overflow-hidden hover:border-amber-500/40 hover:bg-white/8 transition-all duration-300 group flex flex-col">
                                            <div class="relative w-full aspect-[4/3] bg-slate-900 overflow-hidden flex-shrink-0">
                                                <img src="{{ $prod->image_url }}" alt="{{ $prod->nama }}"
                                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                                     onerror="this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center text-5xl bg-gradient-to-br from-blue-500/10 to-teal-600/10\'>🥤</div>';"
                                                >
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                                <div class="absolute top-2 right-2 flex items-center gap-1 bg-black/60 backdrop-blur-sm border border-white/10 px-2 py-0.5 rounded-full text-[10px] font-bold text-amber-400">
                                                    ⭐ {{ $prod->formatted_rating }}
                                                </div>
                                            </div>
                                            <div class="p-3.5 flex flex-col gap-2.5 flex-1">
                                                <div class="flex-1">
                                                    <h3 class="font-bold text-white text-sm line-clamp-2 leading-snug">{{ $prod->nama }}</h3>
                                                </div>
                                                <div class="flex items-center justify-between">
                                                    <p class="text-amber-400 font-extrabold text-sm">Rp {{ number_format($prod->harga, 0, ',', '.') }}</p>
                                                </div>
                                                <button wire:click="addToCart({{ $prod->id }})"
                                                        class="w-full bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-400 hover:to-orange-500 text-white font-bold py-2.5 rounded-xl transition-all shadow-lg shadow-amber-500/20 text-xs flex items-center justify-center gap-1.5 active:scale-95">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                    Tambah
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif
                @endif
            </div>

            <!-- Right: Cart (sticky on desktop) -->
            <div class="lg:w-80 xl:w-96 flex-shrink-0">
                <div class="lg:sticky lg:top-20">
                    <div class="bg-slate-900/80 border border-white/10 backdrop-blur-xl rounded-3xl overflow-hidden shadow-2xl shadow-black/40">
                        <!-- Cart Header -->
                        <div class="px-5 py-4 border-b border-white/10 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 bg-amber-500/15 rounded-xl flex items-center justify-center">
                                    <span class="text-lg">🛒</span>
                                </div>
                                <h2 class="font-black text-white text-base">Keranjang</h2>
                            </div>
                            @if(!empty($cart))
                                <span class="bg-amber-500 text-slate-950 font-black text-xs px-2.5 py-1 rounded-full">
                                    {{ collect($cart)->sum('qty') }}
                                </span>
                            @endif
                        </div>

                        @if(empty($cart))
                            <div class="py-14 text-center px-6">
                                <p class="text-4xl mb-3">🍽️</p>
                                <p class="text-sm font-semibold text-slate-400">Keranjang kosong</p>
                                <p class="text-xs text-slate-600 mt-1">Tambahkan menu di sebelah kiri</p>
                            </div>
                        @else
                            <!-- Cart Items -->
                            <div class="divide-y divide-white/5 max-h-72 overflow-y-auto">
                                @foreach($cart as $id => $item)
                                    <div class="flex items-center gap-3 px-5 py-3.5">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-white text-sm truncate">{{ $item['nama'] }}</h4>
                                            <p class="text-amber-400 text-xs font-bold mt-0.5">Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}</p>
                                        </div>
                                        <div class="flex items-center gap-1.5 flex-shrink-0">
                                            <button wire:click="updateQty({{ $id }}, -1)" class="w-7 h-7 rounded-lg bg-white/10 hover:bg-white/20 text-white font-bold text-base flex items-center justify-center transition leading-none">−</button>
                                            <span class="text-sm font-black text-white w-5 text-center tabular-nums">{{ $item['qty'] }}</span>
                                            <button wire:click="updateQty({{ $id }}, 1)" class="w-7 h-7 rounded-lg bg-amber-500/20 hover:bg-amber-500/30 text-amber-400 font-bold text-base flex items-center justify-center transition leading-none">+</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Cart Footer -->
                            <div class="px-5 py-5 border-t border-white/10 space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400 text-sm font-medium">Subtotal</span>
                                    <span class="text-white font-black text-lg">
                                        Rp {{ number_format(collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']), 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-2 bg-white/5 border border-white/10 rounded-xl px-3.5 py-2.5 text-xs">
                                    <span class="text-slate-500">💳</span>
                                    <span class="text-slate-300 font-medium">Pembayaran di Kasir</span>
                                </div>

                                <button wire:click="checkout"
                                        wire:loading.attr="disabled"
                                        class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-extrabold py-4 px-4 rounded-2xl transition-all shadow-xl shadow-emerald-500/20 flex items-center justify-center gap-2.5 text-sm active:scale-[0.98] disabled:opacity-60">
                                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Pesan Sekarang
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>