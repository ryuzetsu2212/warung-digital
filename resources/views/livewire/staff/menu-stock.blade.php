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

            <!-- MANAJEMEN MENU & STOK CONTENT -->
            <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 shadow-xl space-y-6">
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
                            <span>🍲</span> Manajemen Menu
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">Kelola menu: tambah, edit, hapus, atau ubah ketersediaan menu.</p>
                    </div>
                    <button wire:click="openCreateModal" 
                            class="bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-500 hover:to-green-500 text-white font-bold py-3 px-5 rounded-xl transition shadow-lg flex items-center gap-2 text-sm">
                        <span class="text-lg">➕</span> Tambah Menu Baru
                    </button>
                </div>

                <!-- GRID MENU – TANPA TULISAN "TERSEDIA" SAMA SEKALI -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($allProducts as $prod)
                        <div class="bg-slate-900 border {{ $prod->is_available ? 'border-slate-700' : 'border-red-500/50 bg-red-950/10' }} rounded-2xl overflow-hidden shadow flex flex-col h-full">
                            <!-- Bagian Gambar (tanpa label status) -->
                            <div class="relative w-full h-44 bg-slate-800 overflow-hidden flex items-center justify-center">
                                <img src="{{ $prod->image_url }}" alt="{{ $prod->nama }}"
                                     class="w-full h-full object-cover object-center"
                                     onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\'text-slate-400 text-sm text-center p-4\'>Gambar tidak tersedia</div>';"
                                >
                                <!-- Label status di pojok kanan atas DIHAPUS -->
                            </div>

                            <!-- Konten card (tanpa teks status di bawah harga) -->
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

                                    <!-- Bagian status di bawah harga DIHAPUS -->
                                </div>

                                <!-- Tombol aksi -->
                                <div class="mt-4 pt-3 border-t border-slate-800 space-y-2">
                                    <div class="grid grid-cols-2 gap-2">
                                        <button wire:click="openEditModal({{ $prod->id }})"
                                                class="py-2 px-3 rounded-xl font-bold text-xs transition shadow flex items-center justify-center gap-1
                                                bg-blue-600/20 hover:bg-blue-600 text-blue-300 hover:text-white border border-blue-500/30">
                                            <span>✏️</span> Edit
                                        </button>
                                        <button wire:click="confirmDelete({{ $prod->id }})"
                                                class="py-2 px-3 rounded-xl font-bold text-xs transition shadow flex items-center justify-center gap-1
                                                bg-red-600/20 hover:bg-red-600 text-red-300 hover:text-white border border-red-500/30">
                                            <span>🗑️</span> Hapus
                                        </button>
                                    </div>
                                    <button wire:click="toggleProductAvailability({{ $prod->id }})"
                                            class="w-full py-2.5 px-3 rounded-xl font-bold text-xs transition shadow flex items-center justify-center gap-1.5
                                            @if($prod->is_available) bg-orange-600/20 hover:bg-orange-600 text-orange-300 hover:text-white border border-orange-500/30
                                            @else bg-emerald-600 hover:bg-emerald-500 text-white border border-emerald-500/30 @endif">
                                        @if($prod->is_available)
                                            <span>🔴</span> Tandai Habis
                                        @else
                                            <span>🟢</span> Tandai Tersedia
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Modal Tambah/Edit Menu -->
        @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" 
             x-data="{ show: @entangle('showModal') }"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="bg-slate-900 border border-slate-700 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto"
                 @click.away="$wire.closeModal()"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                
                <div class="p-6 border-b border-slate-700 flex justify-between items-center sticky top-0 bg-slate-900 z-10">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <span>{{ $isEditing ? '✏️' : '➕' }}</span>
                        {{ $isEditing ? 'Edit Menu' : 'Tambah Menu Baru' }}
                    </h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-white transition text-2xl">&times;</button>
                </div>

                @if($errorMessage)
                    <div class="mx-6 mt-4 bg-red-500/10 border border-red-500/40 text-red-300 px-4 py-3 rounded-xl text-sm">
                        {{ $errorMessage }}
                    </div>
                @endif

                <form wire:submit.prevent="saveProduct" class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-300 mb-2">Nama Menu *</label>
                        <input type="text" wire:model="nama" required
                               class="w-full bg-slate-950/60 border border-slate-700 rounded-xl px-4 py-3 text-slate-100 placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition"
                               placeholder="Contoh: Nasi Goreng Special">
                        @error('nama') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-300 mb-2">Kategori *</label>
                        <select wire:model="kategori" required
                                class="w-full bg-slate-950/60 border border-slate-700 rounded-xl px-4 py-3 text-slate-100 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition">
                            <option value="">Pilih Kategori</option>
                            <option value="makanan" {{ strtolower($kategori) === 'makanan' ? 'selected' : '' }}>Makanan</option>
                            <option value="minuman" {{ strtolower($kategori) === 'minuman' ? 'selected' : '' }}>Minuman</option>
                        </select>
                        @error('kategori') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-300 mb-2">Harga *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">Rp</span>
                            <input type="number" wire:model="harga" required min="0" step="1000"
                                   class="w-full bg-slate-950/60 border border-slate-700 rounded-xl pl-12 pr-4 py-3 text-slate-100 placeholder-slate-500 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition"
                                   placeholder="15000">
                        </div>
                        @error('harga') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>



                    <div>
                        <label class="block text-sm font-bold text-slate-300 mb-2">Upload Gambar (Opsional)</label>
                        <input type="file" wire:model="image_file" accept="image/*"
                               class="w-full bg-slate-950/60 border border-slate-700 rounded-xl px-4 py-3 text-slate-100 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-amber-600 file:text-white hover:file:bg-amber-500 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition">
                        @error('image_file') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                        @if ($image_file)
                            <p class="text-xs text-emerald-400 mt-2">✓ File dipilih: {{ $image_file->getClientOriginalName() }}</p>
                        @endif
                    </div>

                    <div class="flex gap-3 pt-4 border-t border-slate-700">
                        <button type="button" wire:click="closeModal"
                                class="flex-1 bg-slate-700 hover:bg-slate-600 text-white font-bold py-3 px-4 rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 text-white font-bold py-3 px-4 rounded-xl transition shadow-lg">
                            {{ $isEditing ? 'Simpan Perubahan' : 'Tambah Menu' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- Modal Konfirmasi Hapus -->
        @if($showDeleteConfirm)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-data="{ show: @entangle('showDeleteConfirm') }"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="bg-slate-900 border border-red-500/50 rounded-2xl shadow-2xl w-full max-w-md"
                 @click.away="$wire.closeDeleteConfirm()"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                
                <div class="p-6 text-center">
                    <div class="w-16 h-16 bg-red-500/10 border-2 border-red-500/40 rounded-full mx-auto flex items-center justify-center text-3xl mb-4">
                        ⚠️
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Hapus Menu?</h3>
                    <p class="text-slate-300 text-sm mb-1">Anda akan menghapus menu:</p>
                    <p class="text-amber-400 font-bold text-lg mb-4">"{{ $deletingProductName }}"</p>
                    <p class="text-slate-400 text-xs mb-6">Tindakan ini tidak dapat dibatalkan.</p>

                    <div class="flex gap-3">
                        <button wire:click="closeDeleteConfirm"
                                class="flex-1 bg-slate-700 hover:bg-slate-600 text-white font-bold py-3 px-4 rounded-xl transition">
                            Batal
                        </button>
                        <button wire:click="deleteProduct"
                                class="flex-1 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 text-white font-bold py-3 px-4 rounded-xl transition shadow-lg">
                            Ya, Hapus Menu
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif
</div>
