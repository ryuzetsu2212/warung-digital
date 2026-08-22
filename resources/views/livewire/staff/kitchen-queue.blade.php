<div wire:poll.3s class="max-w-7xl mx-auto px-4 py-8" x-init="
    Livewire.on('session-expired', () => { window.location.reload(); });
">
    @if(!$isLoggedIn)
        <!-- Form Login Staff - Modern & Clean UI -->
        <div class="min-h-[80vh] flex items-center justify-center px-4">
            <div class="max-w-md w-full bg-slate-900/90 backdrop-blur-xl border border-slate-800 rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden">
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
                    <a href="{{ route('customer.welcome') }}" class="text-xs text-slate-400 hover:text-amber-400 font-medium transition inline-flex items-center gap-1.5">
                        ← Kembali ke Beranda Pelanggan
                    </a>
                </div>
            </div>
        </div>
    @else
        <div>
            <!-- SCHEDULE & INFO BANNER -->
            <div class="bg-gradient-to-r from-slate-800 to-indigo-950/40 border border-slate-700 rounded-2xl p-5 mb-8 shadow-md flex flex-wrap justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">🕒</span>
                    <div>
                        <h3 class="text-white font-bold text-sm">Jadwal Operasional Warung Digital</h3>
                        <p class="text-xs text-slate-300">Buka setiap hari <strong>Senin s.d. Minggu</strong> pukul <strong>07:00 - 17:00 WIB</strong> dan <strong>19:00 - 23:00 WIB</strong></p>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <div class="text-right">
                        <span class="text-xs text-slate-400 block">Pendapatan Hari Ini</span>
                        <span class="text-lg font-black text-amber-400">Rp {{ number_format($revenueToday, 0, ',', '.') }}</span>
                    </div>
                    <div class="text-right border-l border-slate-700 pl-6">
                        <span class="text-xs text-slate-400 block">Pesanan Selesai</span>
                        <span class="text-lg font-black text-emerald-400">{{ $completedTodayCount }} Order</span>
                    </div>
                </div>
            </div>

            <!-- ANTREAN DAPUR CONTENT -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- KOLOM MAKANAN -->
                <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 shadow-xl">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6 border-b border-slate-700 pb-3">
                        <h2 class="text-xl font-bold text-amber-400 flex items-center gap-2">
                            <span>🍲 Antrean Makanan</span>
                            <span class="text-xs bg-amber-500/20 text-amber-300 px-3 py-1 rounded-full font-semibold">
                                {{ count($makananItems) }} Pending
                            </span>
                        </h2>
                        @if(!$makananItems->isEmpty())
                            <div class="flex items-center gap-2">
                                <button wire:click="processAllCategory('makanan')" class="bg-blue-600 hover:bg-blue-500 text-white text-[11px] font-bold px-3 py-1.5 rounded-xl transition shadow">
                                    ⚡ Proses Semua
                                </button>
                                <button wire:click="completeAllCategory('makanan')" class="bg-emerald-600 hover:bg-emerald-500 text-white text-[11px] font-bold px-3 py-1.5 rounded-xl transition shadow">
                                    ✅ Selesaikan Semua
                                </button>
                            </div>
                        @endif
                    </div>

                    @if($makananItems->isEmpty())
                        <div class="text-center py-16 text-slate-500">
                            <p class="text-3xl mb-2">✨</p>
                            <p class="text-sm">Tidak ada antrean makanan saat ini.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($makananItems as $item)
                                <div class="bg-slate-700/50 border border-slate-600 rounded-2xl p-5 shadow flex flex-col justify-between gap-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <span class="bg-indigo-600/30 text-indigo-300 text-xs font-bold px-2.5 py-1 rounded-lg">
                                                Meja {{ $item->order->table->nomor_meja }}
                                            </span>
                                            <h3 class="text-lg font-bold text-slate-100 mt-2">{{ $item->product->nama }}</h3>
                                            <p class="text-xs text-slate-400 mt-1">
                                                Jumlah: <span class="text-white font-bold">{{ $item->qty }}</span> | 
                                                Masuk: {{ $item->created_at->format('H:i:s') }}
                                            </p>
                                        </div>
                                        <div>
                                            <span class="text-xs px-3 py-1.5 rounded-xl font-bold uppercase
                                                @if($item->status_item === 'menunggu') bg-amber-500/20 text-amber-300 border border-amber-500/30
                                                @else bg-blue-500/20 text-blue-300 border border-blue-500/30
                                                @endif">
                                                {{ ucfirst($item->status_item) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-600">
                                        @if($item->status_item === 'menunggu')
                                            <button wire:click="updateItemStatus({{ $item->id }}, 'diproses')" 
                                                    class="bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs px-4 py-2 rounded-xl transition shadow">
                                                Proses
                                            </button>
                                        @endif
                                        <button wire:click="updateItemStatus({{ $item->id }}, 'selesai')" 
                                                class="bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs px-4 py-2 rounded-xl transition shadow">
                                            Selesai
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- KOLOM MINUMAN -->
                <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 shadow-xl">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6 border-b border-slate-700 pb-3">
                        <h2 class="text-xl font-bold text-cyan-400 flex items-center gap-2">
                            <span>🥤 Antrean Minuman</span>
                            <span class="text-xs bg-cyan-500/20 text-cyan-300 px-3 py-1 rounded-full font-semibold">
                                {{ count($minumanItems) }} Pending
                            </span>
                        </h2>
                        @if(!$minumanItems->isEmpty())
                            <div class="flex items-center gap-2">
                                <button wire:click="processAllCategory('minuman')" class="bg-blue-600 hover:bg-blue-500 text-white text-[11px] font-bold px-3 py-1.5 rounded-xl transition shadow">
                                    ⚡ Proses Semua
                                </button>
                                <button wire:click="completeAllCategory('minuman')" class="bg-emerald-600 hover:bg-emerald-500 text-white text-[11px] font-bold px-3 py-1.5 rounded-xl transition shadow">
                                    ✅ Selesaikan Semua
                                </button>
                            </div>
                        @endif
                    </div>

                    @if($minumanItems->isEmpty())
                        <div class="text-center py-16 text-slate-500">
                            <p class="text-3xl mb-2">✨</p>
                            <p class="text-sm">Tidak ada antrean minuman saat ini.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($minumanItems as $item)
                                <div class="bg-slate-700/50 border border-slate-600 rounded-2xl p-5 shadow flex flex-col justify-between gap-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <span class="bg-indigo-600/30 text-indigo-300 text-xs font-bold px-2.5 py-1 rounded-lg">
                                                Meja {{ $item->order->table->nomor_meja }}
                                            </span>
                                            <h3 class="text-lg font-bold text-slate-100 mt-2">{{ $item->product->nama }}</h3>
                                            <p class="text-xs text-slate-400 mt-1">
                                                Jumlah: <span class="text-white font-bold">{{ $item->qty }}</span> | 
                                                Masuk: {{ $item->created_at->format('H:i:s') }}
                                            </p>
                                        </div>
                                        <div>
                                            <span class="text-xs px-3 py-1.5 rounded-xl font-bold uppercase
                                                @if($item->status_item === 'menunggu') bg-amber-500/20 text-amber-300 border border-amber-500/30
                                                @else bg-blue-500/20 text-blue-300 border border-blue-500/30
                                                @endif">
                                                {{ ucfirst($item->status_item) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-600">
                                        @if($item->status_item === 'menunggu')
                                            <button wire:click="updateItemStatus({{ $item->id }}, 'diproses')" 
                                                    class="bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs px-4 py-2 rounded-xl transition shadow">
                                                Proses
                                            </button>
                                        @endif
                                        <button wire:click="updateItemStatus({{ $item->id }}, 'selesai')" 
                                                class="bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs px-4 py-2 rounded-xl transition shadow">
                                            Selesai
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>