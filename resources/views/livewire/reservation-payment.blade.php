<div class="min-h-screen bg-slate-950 text-slate-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-xl mx-auto">
        <!-- Card Utama Pembayaran -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-amber-500/10 border border-amber-500/20 rounded-2xl mx-auto flex items-center justify-center text-3xl mb-4">
                    💳
                </div>
                <h1 class="text-2xl font-black text-white">Pembayaran Reservasi</h1>
                <p class="text-xs text-slate-400 mt-1">Transfer DP 50% ke nomor Dana/OVO yang tersedia</p>
            </div>

             <!-- Summary Reservasi -->
             <div class="bg-slate-950/60 border border-slate-800/80 rounded-2xl p-5 mb-6 space-y-3 text-sm sticky top-0 z-10">
                <div class="flex justify-between items-center pb-3 border-b border-slate-800">
                    <span class="text-slate-400">Nomor Meja</span>
                    <span class="font-bold text-white">Meja {{ $reservation->table->nomor_meja ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-slate-800">
                    <span class="text-slate-400">Jadwal Reservasi</span>
                    <span class="font-bold text-amber-400">
                        {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d M Y') }},
                        {{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }}
                    </span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-slate-800">
                    <span class="text-slate-400">Jumlah Tamu</span>
                    <span class="font-medium text-white">{{ $reservation->number_of_guests }} Orang</span>
                </div>
                @if($reservation->reservationItems->isNotEmpty())
                    <div class="pt-2">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-2">Pesanan Makanan</span>
                        <div class="space-y-1.5 pl-2 border-l-2 border-slate-800">
                            @foreach($reservation->reservationItems as $item)
                                <div class="flex justify-between text-xs">
                                    <span class="text-slate-300">{{ $item->qty }}x {{ $item->product->nama ?? 'Menu' }}</span>
                                    <span class="text-slate-400">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="flex justify-between items-center pt-3 border-t border-slate-800 text-base">
                    <span class="font-medium text-slate-300">Total Tagihan Menu</span>
                    <span class="font-bold text-white">Rp {{ number_format($reservation->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center bg-amber-500/10 p-3 rounded-xl border border-amber-500/20 text-base">
                    <span class="font-bold text-amber-300">Minimal DP Dibayar (50%)</span>
                    <span class="font-black text-amber-400 text-lg">Rp {{ number_format($reservation->dp_amount, 0, ',', '.') }}</span>
                </div>
            </div>

                <!-- Instruksi Pembayaran Manual Dana/OVO -->
                <div class="bg-gradient-to-br from-green-900/20 to-blue-900/20 border border-green-500/30 rounded-2xl p-6 mb-6 space-y-4">
                    <div class="text-center mb-4">
                        <div class="inline-flex items-center gap-2 bg-green-500/10 px-4 py-2 rounded-xl border border-green-500/20">
                            <span class="text-2xl">💰</span>
                            <span class="font-bold text-green-400 text-sm">Transfer Manual</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="bg-slate-950/60 border border-slate-700 rounded-xl p-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs text-slate-400 font-bold uppercase">Nomor Dana/OVO</span>
                                <button onclick="navigator.clipboard.writeText('082285011556')"
                                        class="text-xs bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 px-3 py-1 rounded-lg border border-amber-500/20 transition">
                                    📋 Salin
                                </button>
                            </div>
                            <div class="font-black text-2xl text-amber-400 tracking-wider">
                                082285011556
                            </div>
                        </div>

                        <div class="bg-slate-950/60 border border-slate-700 rounded-xl p-4">
                            <div class="text-xs text-slate-400 font-bold uppercase mb-2">Jumlah Transfer</div>
                            <div class="font-black text-xl text-green-400">
                                Rp {{ number_format($reservation->dp_amount, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <!-- Form Upload Bukti Pembayaran -->
                    <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-4 mt-4">
                        <div class="flex gap-3">
                            <div class="text-2xl">📸</div>
                            <div class="text-xs text-blue-300 leading-relaxed">
                                <p class="font-bold mb-2">Upload Bukti Pembayaran:</p>
                                <form wire:submit.prevent="uploadPaymentProof" class="space-y-3">
                                      <div>
                                           <input type="file" wire:model="paymentProof" id="paymentProof" accept="image/*" class="block w-full text-sm text-blue-300
                                               file:mr-4 file:py-2 file:px-4
                                               file:rounded-lg file:border-0
                                               file:text-sm file:font-semibold
                                               file:bg-blue-500 file:text-white
                                               hover:file:bg-blue-600
                                               cursor-pointer">
                                           <p class="text-xs text-slate-400 mt-1">
                                               Format: JPG, PNG, JPEG • Maks: 2MB
                                           </p>
                                           <p id="selectedFileName" class="text-xs text-blue-200 mt-2 text-center">
                                               📄 <span id="fileName"></span>
                                           </p>
                                           @error('paymentProof') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                       </div>
                                     <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition" wire:loading.attr="disabled">
                                         <span wire:loading.remove wire:target="paymentProof,uploadPaymentProof">Unggah Bukti Pembayaran</span>
                                         <span wire:loading wire:target="paymentProof,uploadPaymentProof">Memproses...</span>
                                     </button>
                                 </form>
                                 <script>
                                     function updateFileName(input) {
                                         const fileNameDisplay = document.getElementById('fileNameDisplay');
                                         const selectedFileName = document.getElementById('selectedFileName');
                                         const fileName = document.getElementById('fileName');
                                         
                                         if (input.files && input.files[0]) {
                                             fileNameDisplay.textContent = input.files[0].name;
                                             fileName.textContent = input.files[0].name;
                                             selectedFileName.classList.remove('hidden');
                                         } else {
                                             fileNameDisplay.textContent = 'Pilih File Bukti Pembayaran';
                                             selectedFileName.classList.add('hidden');
                                         }
                                     }
                                 </script>
                                 @if (session()->has('message'))
                                     <div class="text-green-400 text-xs mt-2">
                                         {{ session('message') }}
                                     </div>
                                 @endif
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-4 mt-4">
                        <div class="flex gap-3">
                            <div class="text-2xl">ℹ️</div>
                            <div class="text-xs text-blue-300 leading-relaxed">
                                <p class="font-bold mb-2">Cara Pembayaran:</p>
                                <ol class="list-decimal list-inside space-y-1">
                                    <li>Transfer DP 50% ke nomor Dana/OVO di atas</li>
                                    <li>Upload bukti pembayaran di atas</li>
                                    <li>Tunggu konfirmasi dari staff kami</li>
                                    <li>Setelah dikonfirmasi, reservasi Anda akan diaktifkan</li>
                                    <li>Sisa pembayaran dapat dilunasi saat di tempat</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    @if($reservation->payment_status === 'dp_pending')
                        <div class="bg-amber-500/10 border border-amber-500/20 rounded-xl p-4 mt-4 text-center">
                            <div class="text-amber-400 font-bold text-sm mb-1">⏳ Menunggu Konfirmasi</div>
                            <div class="text-xs text-slate-400">
                                Silakan transfer dan tunggu staff mengkonfirmasi pembayaran Anda
                            </div>
                        </div>
                    @elseif($reservation->payment_status === 'dp_paid')
                        <div class="bg-green-500/10 border border-green-500/20 rounded-xl p-4 mt-4 text-center">
                            <div class="text-green-400 font-bold text-sm mb-1">✅ DP Sudah Dikonfirmasi</div>
                            <div class="text-xs text-slate-400">
                                Pembayaran DP Anda sudah dikonfirmasi oleh staff
                            </div>
                        </div>
                    @endif
                </div>

            <!-- Navigasi -->
            <div class="mt-6 text-center">
                <a href="{{ route('customer.reservations') }}" class="text-xs text-slate-500 hover:text-slate-300 transition">
                    ← Lihat Semua Reservasi Saya
                </a>
            </div>
        </div>
    </div>
</div>