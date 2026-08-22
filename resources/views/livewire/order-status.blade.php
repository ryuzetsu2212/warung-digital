<div wire:poll.3s class="max-w-2xl mx-auto px-4 py-12">
    <div class="bg-slate-800 border border-slate-700 rounded-2xl shadow-xl p-6 sm:p-8 text-center space-y-6">
        <div>
            <span class="text-4xl">🕒</span>
            <h1 class="text-2xl font-bold text-slate-100 mt-2">Status Pesanan Anda</h1>
            <p class="text-slate-400 text-sm">Meja Nomor: <span class="text-white font-bold">{{ $order->table->nomor_meja }}</span> | ID Pesanan: #{{ $order->id }}</p>
        </div>

        <!-- Status Utama Order -->
            <div class="inline-block px-6 py-2 rounded-full text-sm font-bold uppercase tracking-wider
                @if($order->status === 'menunggu') bg-amber-500/20 text-amber-400 border border-amber-500/50
                @elseif($order->status === 'diproses') bg-blue-500/20 text-blue-400 border border-blue-500/50
                @else bg-emerald-500/20 text-emerald-400 border border-emerald-500/50
                @endif">
                Status Utama: {{ ucfirst($order->status) }} - {{ ucfirst(str_replace('_', ' ', $order->status_pembayaran)) }}
            </div>

        <div class="border-t border-slate-700 pt-6 text-left">
            <h3 class="font-bold text-slate-200 mb-4">Rincian Item Pesanan:</h3>
            <div class="space-y-3">
                @foreach($order->orderItems as $item)
                    <div class="bg-slate-700/50 border border-slate-600 p-4 rounded-xl flex items-center justify-between">
                        <div>
                            <h4 class="font-semibold text-slate-100">{{ $item->product->nama }}</h4>
                            <p class="text-xs text-slate-400">Qty: {{ $item->qty }} | Kategori: <span class="capitalize text-amber-400">{{ $item->kategori_item }}</span></p>
                        </div>
                        <div>
                            <span class="text-xs px-3 py-1 rounded-lg font-semibold uppercase
                                @if($item->status_item === 'menunggu') bg-amber-500/20 text-amber-300
                                @elseif($item->status_item === 'diproses') bg-blue-500/20 text-blue-300
                                @else bg-emerald-500/20 text-emerald-300
                                @endif">
                                {{ ucfirst($item->status_item) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Fitur Penilaian / Rating jika pesanan sudah selesai -->
        @if($order->status === 'selesai')
            <div class="border-t border-slate-700 pt-6 text-left bg-slate-900/40 p-5 rounded-xl border border-slate-700">
                <h3 class="font-bold text-slate-100 text-center mb-2">Beri Penilaian & Ulasan</h3>
                <p class="text-xs text-slate-400 text-center mb-4">Bagaimana pengalaman Anda menikmati hidangan kami?</p>

                @if($successMessage)
                    <div class="mb-4 bg-emerald-500/20 border border-emerald-500 text-emerald-300 text-xs p-3 rounded-lg text-center">
                        {{ $successMessage }}
                    </div>
                @endif

                <form wire:submit.prevent="submitRating" class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-1">Rating (Bintang):</label>
                        <select wire:model="rating" class="w-full bg-slate-800 border border-slate-700 text-slate-100 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-amber-500">
                            <option value="5">⭐⭐⭐⭐⭐ (5 - Sangat Memuaskan)</option>
                            <option value="4">⭐⭐⭐⭐ (4 - Memuaskan)</option>
                            <option value="3">⭐⭐⭐ (3 - Cukup)</option>
                            <option value="2">⭐⭐ (2 - Kurang)</option>
                            <option value="1">⭐ (1 - Buruk)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-300 mb-1">Ulasan / Masukan (Opsional):</label>
                        <textarea wire:model="review" rows="2" placeholder="Tuliskan ulasan atau masukan untuk warung kami..."
                                  class="w-full bg-slate-800 border border-slate-700 text-slate-100 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-amber-500"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold py-2.5 rounded-xl text-sm transition shadow-lg shadow-amber-500/20">
                        {{ $order->rating ? 'Perbarui Penilaian' : 'Kirim Penilaian' }}
                    </button>
                </form>
            </div>
        @endif

        <div class="border-t border-slate-700 pt-6 flex flex-col sm:flex-row justify-center items-center gap-4">
            @if($order->status === 'menunggu')
                <button wire:click="cancelOrder" wire:confirm="Apakah Anda yakin ingin membatalkan pesanan ini?"
                        class="bg-red-600/30 hover:bg-red-600/50 text-red-300 border border-red-500/30 font-medium px-6 py-2.5 rounded-xl text-sm transition">
                    Batalkan Pesanan
                </button>
            @endif
            <a href="{{ route('customer.menu', $order->table->uuid) }}" 
               class="bg-slate-700 hover:bg-slate-600 text-slate-200 font-medium px-6 py-2.5 rounded-xl text-sm transition">
                ← Kembali ke Menu / Pesan Lagi
            </a>
        </div>
    </div>
</div>