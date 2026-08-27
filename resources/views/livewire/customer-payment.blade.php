<div class="max-w-2xl mx-auto px-4 py-8" wire:poll.3s>
    <!-- Header -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl mb-6 text-center relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-indigo-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <span class="text-4xl mb-3 block">💳</span>
        <h1 class="text-2xl font-black text-white">Pembayaran Pesanan</h1>
        <p class="text-slate-400 text-xs sm:text-sm mt-1">Meja Nomor: <strong class="text-amber-400">{{ $order->table->nomor_meja ?? '-' }}</strong> | ID Pesanan: <strong class="text-indigo-400">#{{ $order->id }}</strong></p>

        <div class="mt-4 inline-block 
            @if($order->status_pembayaran === 'lunas') bg-emerald-500/10 border border-emerald-500/30 text-emerald-300
            @elseif($order->status_pembayaran === 'menunggu_konfirmasi') bg-amber-500/10 border border-amber-500/30 text-amber-300
            @else bg-slate-800 border border-slate-700 text-slate-300 @endif
            px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider">
            Status: {{ ucfirst($order->status) }} - 
            @if($order->status_pembayaran === 'lunas') Sudah Dibayar (Lunas)
            @elseif($order->status_pembayaran === 'menunggu_konfirmasi') Menunggu Konfirmasi Kasir
            @else Belum Dibayar @endif
        </div>
    </div>

    @if($successMessage)
        <div class="mb-6 bg-emerald-500/10 border border-emerald-500/40 text-emerald-300 px-5 py-4 rounded-2xl text-sm text-center font-medium shadow-lg">
            {{ $successMessage }}
        </div>
    @endif


    <!-- Rincian Pembayaran -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl mb-6">
        <h2 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
            <span>📋</span> Rincian Tagihan
        </h2>

        <div class="space-y-3 mb-6 divide-y divide-slate-800/60">
            @php $total = 0; @endphp
            @foreach($order->orderItems as $item)
                @php
                    $subtotal = ($item->product && $item->status_item !== 'dibatalkan') ? $item->product->harga * $item->qty : 0;
                    $total += $subtotal;
                @endphp
                <div class="pt-3 first:pt-0 flex justify-between items-center text-sm">
                    <div>
                        <span class="text-white font-medium block">{{ $item->product->nama ?? 'Menu' }}</span>
                        <span class="text-xs text-slate-400">{{ $item->qty }}x @ Rp {{ number_format($item->product->harga ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <span class="font-bold text-slate-200">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>

        <div class="pt-4 border-t border-slate-700 flex justify-between items-center">
            <span class="text-base font-bold text-slate-300">Total Pembayaran</span>
            <span class="text-2xl font-black text-amber-400">Rp {{ number_format($total, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Form Pilih Metode Pembayaran / Silakan Lakukan Pembayaran ke Kasir & Struk Download -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl mb-6">
        <h2 class="text-lg font-bold text-white mb-4">Status & Struk Pembayaran</h2>

        <div class="space-y-4">
            <div class="mb-4">
                <span class="text-xs text-slate-400 block mb-1 uppercase tracking-wider font-semibold">Metode Pembayaran:</span>
                <span class="text-white font-bold text-base">
                    @if($order->status_pembayaran === 'lunas')
                        {{ ucfirst($order->metode_pembayaran ?? 'Kasir') }}
                    @else
                        Tunai (Bayar di Kasir)
                    @endif
                </span>
            </div>

            @if($order->status_pembayaran === 'lunas')
                <div class="w-full bg-emerald-600/20 border border-emerald-500/40 text-emerald-300 font-bold py-4 px-4 rounded-2xl text-center text-sm shadow-lg mb-4">
                    Pembayaran Lunas (via Kasir)! Silakan Unduh Struk Pesanan Anda di Bawah.
                </div>

                <!-- Receipt Element for html2canvas PNG download -->
                <div id="receipt-container" style="position: relative; margin-bottom: 16px; width: 100%; background: #ffffff; color: #111111; padding: 24px; font-family: 'Segoe UI', Arial, sans-serif; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.3);">
                    <div style="text-align: center; border-bottom: 2px dashed #333; padding-bottom: 12px; margin-bottom: 16px;">
                        <h2 style="font-size: 18px; font-weight: bold; margin: 0;">WARUNG DIGITAL</h2>
                        <p style="font-size: 11px; color: #555; margin: 4px 0 0 0;">Struk Bukti Pembayaran Lunas</p>
                    </div>
                    <div style="font-size: 12px; margin-bottom: 12px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                            <span>ID Pesanan:</span>
                            <strong>#{{ $order->id }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                            <span>Meja:</span>
                            <strong>{{ $order->table->nomor_meja ?? '-' }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                            <span>Pemesan:</span>
                            <strong>{{ $order->customer_name }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>Waktu:</span>
                            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                    <div style="border-top: 1px solid #ddd; border-bottom: 1px solid #ddd; padding: 10px 0; margin-bottom: 12px;">
                        @php $receiptTotal = 0; @endphp
                        @foreach($order->orderItems as $item)
                            @php
                                $sub = ($item->product && $item->status_item !== 'dibatalkan') ? $item->product->harga * $item->qty : 0;
                                $receiptTotal += $sub;
                            @endphp
                            @if($item->product && $item->status_item !== 'dibatalkan')
                                <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 6px;">
                                    <span>{{ $item->product->nama }} x{{ $item->qty }}</span>
                                    <span>Rp {{ number_format($sub, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 14px; font-weight: bold; margin-bottom: 16px;">
                        <span>TOTAL LUNAS:</span>
                        <span>Rp {{ number_format($receiptTotal, 0, ',', '.') }}</span>
                    </div>
                    <div style="text-align: center; font-size: 10px; color: #666; border-top: 1px dashed #ddd; padding-top: 8px;">
                        Terima Kasih Telah Berkunjung!<br>Simpan Struk Ini Sebagai Bukti Sah.
                    </div>
                </div>

                <button type="button" id="download-receipt-btn" class="w-full bg-emerald-400 hover:bg-emerald-300 text-slate-950 font-black py-4 px-4 rounded-2xl text-base transition shadow-2xl shadow-emerald-500/50 flex items-center justify-center gap-2 cursor-pointer">
                    <span>📥</span> <span style="color: #020617 !important; font-weight: 900 !important;">Unduh Struk Pembayaran (PNG)</span>
                </button>

                <p class="text-xs text-slate-400 text-center mt-3">Silahkan screenshot atau refresh halaman web apabila download gagal</p>

                @once
                <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const downloadBtn = document.getElementById('download-receipt-btn');
                        
                        if (downloadBtn) {
                            downloadBtn.addEventListener('click', function() {
                                // Check if html2canvas is loaded
                                if (typeof html2canvas === 'undefined') {
                                    alert('Mohon tunggu, sedang memuat library...');
                                    return;
                                }
                                
                                const receiptElement = document.getElementById('receipt-container');
                                if (!receiptElement) {
                                    alert('Elemen struk tidak ditemukan');
                                    return;
                                }
                                
                                // Show loading state
                                const originalText = downloadBtn.innerHTML;
                                downloadBtn.innerHTML = '<span>⏳</span> <span style="color: #020617 !important; font-weight: 900 !important;">Sedang Membuat Struk...</span>';
                                downloadBtn.disabled = true;
                                
                                html2canvas(receiptElement, { 
                                    scale: 2, 
                                    useCORS: true, 
                                    logging: false,
                                    backgroundColor: '#ffffff'
                                }).then(canvas => {
                                    const link = document.createElement('a');
                                    link.download = 'Struk-Pembayaran-Pesanan-{{ $order->id }}.png';
                                    link.href = canvas.toDataURL('image/png');
                                    document.body.appendChild(link);
                                    link.click();
                                    document.body.removeChild(link);
                                    
                                    // Restore button state
                                    downloadBtn.innerHTML = originalText;
                                    downloadBtn.disabled = false;
                                }).catch(err => {
                                    console.error('Error generating receipt:', err);
                                    alert('Gagal mengunduh struk. Silakan coba lagi.');
                                    
                                    // Restore button state
                                    downloadBtn.innerHTML = originalText;
                                    downloadBtn.disabled = false;
                                });
                            });
                        }
                    });
                </script>
                @endonce
            @else
                <div class="w-full bg-amber-500/10 border border-amber-500/40 text-amber-300 font-bold py-4 px-4 rounded-2xl text-center text-sm shadow-lg">
                    Silakan menuju kasir untuk melakukan pembayaran.
                </div>
            @endif
        </div>
    </div>

    <!-- Fitur Penilaian / Rating di bawah konfirmasi kasir -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl">
        <h3 class="font-bold text-white text-center mb-1 text-lg">Beri Penilaian & Ulasan</h3>
        <p class="text-xs text-slate-400 text-center mb-6">Bagaimana pengalaman Anda menikmati hidangan kami?</p>

        @if($successMessage && str_contains($successMessage, 'penilaian'))
            <div class="mb-6 bg-emerald-500/10 border border-emerald-500/40 text-emerald-300 text-xs p-3.5 rounded-2xl text-center font-medium">
                {{ $successMessage }}
            </div>
        @endif

        <form wire:submit.prevent="submitRating" class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Rating (Bintang):</label>
                <select wire:model="rating" class="w-full bg-slate-800 border border-slate-700 text-slate-100 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-amber-400 transition">
                    <option value="5">⭐⭐⭐⭐⭐ (5 - Sangat Memuaskan)</option>
                    <option value="4">⭐⭐⭐⭐ (4 - Memuaskan)</option>
                    <option value="3">⭐⭐⭐ (3 - Cukup)</option>
                    <option value="2">⭐⭐ (2 - Kurang)</option>
                    <option value="1">⭐ (1 - Buruk)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Ulasan / Masukan (Opsional):</label>
                <textarea wire:model="review" rows="3" placeholder="Tuliskan ulasan atau masukan untuk warung kami..."
                          class="w-full bg-slate-800 border border-slate-700 text-slate-100 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-amber-400 transition"></textarea>
            </div>

            <button type="submit" class="w-full bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold py-3.5 px-4 rounded-2xl text-sm transition shadow-lg shadow-amber-500/10">
                {{ $order->rating ? 'Perbarui Penilaian' : 'Kirim Penilaian' }}
            </button>
        </form>
    </div>
</div>