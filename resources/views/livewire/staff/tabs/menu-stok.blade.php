<!-- TAB 2: MANAJEMEN MENU & STOK -->
<div x-show="tab === 'menu'" style="display: none;" class="bg-slate-800 border border-slate-700 rounded-2xl p-6 shadow-xl space-y-6">
    @if($successMessage)
        <div x-data="{ show: true }" x-show="show" class="bg-emerald-500/10 border border-emerald-500/40 text-emerald-300 px-5 py-4 rounded-2xl text-sm flex items-center justify-between shadow-lg animate-bounce">
            <div class="flex items-center gap-3">
                <span class="text-2xl">🔔</span>
                <div>
                    <strong class="font-bold block">Informasi Status Menu</strong>
                    <span>{{ $successMessage }}</span>
                </div>
            </div>
            <button wire:click="clearMessage" @click="show = false" class="text-emerald-400 hover:text-white font-bold text-lg px-2">×</button>
        </div>
    @endif

    <div class="flex justify-between items-center border-b border-slate-700 pb-4">
        <div>
            <h2 class="text-xl font-bold text-amber-400 flex items-center gap-2">
                <span>🍲</span> Manajemen Ketersediaan Menu Harian
            </h2>
            <p class="text-xs text-slate-400 mt-1">Sembunyikan atau tampilkan menu yang sedang kosong/habis hari ini agar pelanggan tidak memesannya.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($allProducts as $prod)
            <div class="bg-slate-900 border {{ $prod->is_available ? 'border-slate-700' : 'border-red-500/50 bg-red-950/10' }} rounded-2xl overflow-hidden shadow flex flex-col justify-between">
                <div class="flex flex-col h-full">
                    <div class="relative w-full h-44 bg-slate-900 overflow-hidden">
                        <img src="{{ $prod->image_url }}" alt="{{ $prod->nama }}" class="w-full h-full object-cover object-center">
                        <span class="absolute top-2 right-2 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase shadow
                            @if($prod->is_available) bg-emerald-500 text-slate-950
                            @else bg-red-600 text-white @endif">
                            {{ $prod->is_available ? 'Tersedia' : 'Habis / Tutup' }}
                        </span>
                    </div>
                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $prod->kategori }}</span>
                            <h3 class="font-bold text-slate-100 text-base mt-0.5 line-clamp-1">{{ $prod->nama }}</h3>
                            <p class="text-amber-400 font-extrabold text-sm mt-1">Rp {{ number_format($prod->harga, 0, ',', '.') }}</p>
                            
                            <div class="flex items-center gap-1.5 mt-1 text-xs text-slate-300">
                                <span class="text-amber-400">⭐</span>
                                <span class="font-bold text-white">{{ $prod->formatted_rating }}</span>
                                <span class="text-slate-400">({{ $prod->rating_count }} ulasan)</span>
                            </div>
                            
                             <!-- Penjelasan status rinci -->
                             <div class="mt-3.5 text-[10px] font-normal px-2 py-1 rounded-lg leading-relaxed
                                 @if($prod->is_available) bg-emerald-500/10 text-emerald-300 border border-emerald-500/20
                                 @else bg-red-500/10 text-red-300 border border-red-500/20 @endif">
                                 @if($prod->is_available)
                                     ✓ Status: <strong class="font-semibold">Tersedia</strong>. Menu tampil & dapat dipesan.
                                 @else
                                     ✕ Status: <strong class="font-semibold">Habis</strong>. Menu disembunyikan dari pelanggan.
                                 @endif
                             </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-800">
                            <button wire:click="toggleProductAvailability({{ $prod->id }})" 
                                    class="w-full py-2.5 px-4 rounded-xl font-bold text-xs transition shadow
                                    @if($prod->is_available) bg-red-600/20 text-red-300 hover:bg-red-600/30 border border-red-500/30
                                    @else bg-emerald-600 text-white hover:bg-emerald-500 @endif">
                                {{ $prod->is_available ? 'Tandai Habis (Sembunyikan)' : 'Tandai Tersedia (Tampilkan)' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>