<div class="min-h-screen flex items-center justify-center p-4">
    @if($isSubmitted && $submittedData)
        <!-- SUCCESS CARD -->
        <div class="bg-slate-800/80 backdrop-blur-xl rounded-3xl p-8 shadow-2xl border border-slate-700 w-full max-w-md text-center">
            <div class="w-20 h-20 bg-emerald-500/15 border border-emerald-500/30 rounded-full mx-auto flex items-center justify-center text-4xl mb-5 shadow-inner">
                ✅
            </div>
            <h2 class="text-2xl font-black text-white mb-2">Reservasi Berhasil!</h2>
            <p class="text-sm text-slate-400 mb-6">
                Terima kasih <strong class="text-amber-400">{{ $submittedData->customer_name }}</strong>, reservasi Anda telah kami terima.
            </p>

            <div class="bg-slate-900/60 border border-slate-700 rounded-2xl p-5 text-left space-y-3 mb-6">
                <div class="flex justify-between items-center">
                    <span class="text-xs text-slate-400">Meja</span>
                    <span class="text-sm font-bold text-white">Nomor {{ $submittedData->table->nomor_meja }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-slate-400">Tanggal</span>
                    <span class="text-sm font-bold text-white">{{ \Carbon\Carbon::parse($submittedData->reservation_date)->translatedFormat('d F Y') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-slate-400">Waktu</span>
                    <span class="text-sm font-bold text-white">{{ \Carbon\Carbon::parse($submittedData->reservation_time)->format('H:i') }} WIB</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-slate-400">Jumlah Tamu</span>
                    <span class="text-sm font-bold text-white">{{ $submittedData->number_of_guests }} orang</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-slate-400">Status</span>
                    <span class="text-xs font-bold px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30">Menunggu Konfirmasi</span>
                </div>
            </div>

            <p class="text-xs text-slate-500 mb-6">
                Tim kami akan menghubungi <strong class="text-slate-300">{{ $submittedData->phone_number }}</strong> untuk konfirmasi.
            </p>

            <div class="flex gap-3">
                <a href="{{ url('/') }}" class="flex-1 bg-slate-700 hover:bg-slate-600 text-white font-bold py-3.5 rounded-2xl transition text-sm">
                    Kembali ke Beranda
                </a>
                <button wire:click="resetForm" class="flex-1 bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3.5 rounded-2xl transition text-sm">
                    Buat Reservasi Lagi
                </button>
            </div>
        </div>
    @else
        <!-- RESERVATION FORM -->
        <div class="bg-slate-800/80 backdrop-blur-xl rounded-3xl p-8 shadow-2xl border border-slate-700 w-full max-w-md">
            <h2 class="text-2xl font-black text-white mb-1 text-center">Reservasi Meja</h2>
            <p class="text-xs text-slate-400 text-center mb-6">Pesan meja Anda di Warung Digital QR</p>

            <form wire:submit.prevent="submit" class="space-y-4">
                <div>
                    <label for="table_id" class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Nomor Meja *</label>
                    <select id="table_id" wire:model.live="table_id" class="w-full bg-slate-950/70 border border-slate-600 rounded-2xl px-4 py-3.5 text-slate-100 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition appearance-none cursor-pointer">
                        <option value="">Pilih Nomor Meja</option>
                        @foreach($tables as $table)
                            <option value="{{ $table->id }}">Meja Nomor {{ $table->nomor_meja }}</option>
                        @endforeach
                    </select>
                    @error('table_id') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Table Availability Warning -->
                @if($tableWarning)
                    <div class="bg-amber-500/10 border border-amber-500/30 rounded-2xl p-4 space-y-3">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">⚠️</span>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-amber-300 mb-1">Peringatan Ketersediaan Meja</p>
                                <p class="text-xs text-amber-200/80">{{ $tableWarning }}</p>
                            </div>
                        </div>
                        
                        @if(count($availableTables) > 0)
                            <div class="bg-slate-900/50 rounded-xl p-3 border border-slate-700">
                                <p class="text-xs font-bold text-slate-300 mb-2">💡 Meja yang tersedia:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($availableTables as $altTable)
                                        <button 
                                            type="button"
                                            wire:click="$set('table_id', {{ $altTable->id }})"
                                            class="px-3 py-1.5 bg-emerald-600/20 hover:bg-emerald-600/30 border border-emerald-500/40 rounded-lg text-xs font-bold text-emerald-300 transition">
                                            Meja {{ $altTable->nomor_meja }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="bg-red-900/20 rounded-xl p-3 border border-red-500/30">
                                <p class="text-xs text-red-300">Tidak ada meja lain yang tersedia pada waktu ini. Silakan pilih waktu atau tanggal lain.</p>
                            </div>
                        @endif
                    </div>
                @endif

                <div>
                    <label for="customer_name" class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Nama Pelanggan *</label>
                    <input type="text" id="customer_name" wire:model="customer_name" class="w-full bg-slate-950/50 border border-slate-700 rounded-2xl px-4 py-3 text-slate-100 placeholder-slate-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
                    @error('customer_name') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="phone_number" class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Nomor Telepon *</label>
                    <input type="text" id="phone_number" wire:model="phone_number" class="w-full bg-slate-950/50 border border-slate-700 rounded-2xl px-4 py-3 text-slate-100 placeholder-slate-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
                    @error('phone_number') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="reservation_date" class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Tanggal *</label>
                        <input type="date" id="reservation_date" wire:model.live="reservation_date" class="w-full bg-slate-950/50 border border-slate-700 rounded-2xl px-4 py-3 text-slate-100 placeholder-slate-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
                        @error('reservation_date') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="reservation_time" class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Waktu *</label>
                        <input type="time" id="reservation_time" wire:model.live="reservation_time" class="w-full bg-slate-950/50 border border-slate-700 rounded-2xl px-4 py-3 text-slate-100 placeholder-slate-600 focus:border-indigo-500 focus:ring-indigo-500/20 transition">
                        @error('reservation_time') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label for="number_of_guests" class="block text-xs font-bold text-slate-300 uppercase mb-1.5">Jumlah Tamu *</label>
                    <input type="number" id="number_of_guests" wire:model="number_of_guests" class="w-full bg-slate-950/50 border border-slate-700 rounded-2xl px-4 py-3 text-slate-100 placeholder-slate-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition">
                    @error('number_of_guests') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-black py-4 rounded-2xl transition shadow-lg shadow-indigo-600/20 mt-2">
                    BUAT RESERVASI
                </button>
            </form>
        </div>
    @endif
</div>