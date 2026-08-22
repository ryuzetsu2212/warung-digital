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

            <!-- QR CODE MEJA CONTENT -->
            <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 shadow-xl space-y-6">
                @if($successMessage)
                    <div x-data="{ show: true }" x-show="show" class="bg-emerald-500/10 border border-emerald-500/40 text-emerald-300 px-5 py-4 rounded-2xl text-sm flex items-center justify-between shadow-lg animate-bounce">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">🔔</span>
                            <div>
                                <strong class="font-bold block">Informasi Meja & QR</strong>
                                <span>{{ $successMessage }}</span>
                            </div>
                        </div>
                        <button wire:click="clearMessage" @click="show = false" class="text-emerald-400 hover:text-white font-bold text-lg px-2">×</button>
                    </div>
                @endif

                <div class="flex justify-between items-center border-b border-slate-700 pb-4">
                    <div>
                        <h2 class="text-xl font-bold text-cyan-400 flex items-center gap-2">
                            <span>📷</span> QR Code & Status Sesi Meja Pelanggan
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">Scan QR atau buka link meja untuk simulasi pesanan pelanggan, serta reset sesi meja jika tamu telah selesai.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($tables as $t)
                        @php
                            // Gunakan short_code jika ada, fallback ke uuid
                            $qrUrl = route('customer.menu', ['code' => $t->short_code ?? $t->uuid]);
                        @endphp
                        <div class="bg-slate-900 border border-slate-700 rounded-2xl p-6 shadow-xl flex flex-col justify-between">
                            <div id="qr-card-{{ $t->id }}" class="p-2 bg-slate-900 rounded-xl">
                                <div class="flex justify-between items-center mb-4">
                                    <div>
                                        <h3 class="text-2xl font-black text-white">Meja {{ $t->nomor_meja }}</h3>
                                    </div>
                                    <span class="px-3 py-1 rounded-xl text-xs font-bold uppercase shadow
                                        @if($t->status_meja === 'tersedia') bg-emerald-500/20 text-emerald-300 border border-emerald-500/30
                                        @else bg-amber-500/20 text-amber-300 border border-amber-500/30 @endif">
                                        {{ ucfirst($t->status_meja) }}
                                    </span>
                                </div>

                                <!-- QR Code Render -->
                                <div class="bg-white p-5 rounded-2xl shadow-lg border border-slate-200/60 flex flex-col items-center justify-center my-4">
                                    <div class="text-center mb-3">
                                        <span class="text-[11px] font-bold tracking-wider uppercase text-slate-500">Scan untuk Menu Meja {{ $t->nomor_meja }}</span>
                                    </div>
                                    <div id="qr-svg-{{ $t->id }}" class="w-36 h-36 flex items-center justify-center bg-white rounded-xl overflow-hidden p-1.5 border border-slate-100 shadow-sm mb-4">
                                        {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(136)->format('svg')->generate($qrUrl) !!}
                                    </div>
                                     <div class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-center shadow-md">
                                         <span class="text-[10px] font-mono font-bold break-all block select-all text-cyan-300 tracking-normal">{{ $qrUrl }}</span>
                                     </div>
                                </div>
                            </div>

                            <div class="space-y-3 pt-4 border-t border-slate-800">
                                <a href="{{ $qrUrl }}" target="_blank"
                                   class="w-full bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition shadow flex items-center justify-center gap-2">
                                    <span>🔗</span> Buka Link Menu Meja ini
                                </a>

                                <button type="button" @click="downloadTableQr('{{ $t->id }}', '{{ $t->nomor_meja }}', '{{ $qrUrl }}')"
                                   class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition shadow flex items-center justify-center gap-2 cursor-pointer">
                                    <span>📥</span> Download QR PNG
                                </button>

                                <div class="pt-1">
                                    @if($t->status_meja === 'tersedia')
                                        <button wire:click="setTableOccupied({{ $t->id }})"
                                                class="w-full bg-amber-600/20 hover:bg-amber-600 text-amber-300 hover:text-white border border-amber-500/30 font-semibold py-2.5 px-3 rounded-xl text-xs transition">
                                            🔒 Kunci Meja
                                        </button>
                                    @else
                                        <button wire:click="resetTableSession({{ $t->id }})"
                                                class="w-full bg-red-600/20 hover:bg-red-600 text-red-300 hover:text-white border border-red-500/30 font-semibold py-2.5 px-3 rounded-xl text-xs transition">
                                            🔄 Reset Sesi Meja
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <script>
    function downloadTableQr(tableId, tableNum, qrUrl) {
        const canvas = document.createElement('canvas');
        canvas.width = 600;
        canvas.height = 1000;
        const ctx = canvas.getContext('2d');

        // Background
        ctx.fillStyle = '#0f172a';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        // Card background
        ctx.fillStyle = '#1e293b';
        ctx.beginPath();
        ctx.roundRect(25, 25, 550, 950, 24);
        ctx.fill();

        // Title Meja
        ctx.fillStyle = '#ffffff';
        ctx.font = 'bold 30px sans-serif';
        ctx.fillText('Meja ' + tableNum, 55, 85);

        // QR Container
        ctx.fillStyle = '#ffffff';
        ctx.beginPath();
        ctx.roundRect(55, 115, 490, 395, 20);
        ctx.fill();

        // Text above QR
        ctx.fillStyle = '#64748b';
        ctx.font = 'bold 13px sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('SCAN UNTUK MENU MEJA ' + tableNum, 300, 150);

        const container = document.getElementById('qr-svg-' + tableId);
        if (!container) {
            alert('QR Code tidak ditemukan');
            return;
        }
        const svgEl = container.querySelector('svg');
        if (!svgEl) {
            alert('SVG QR Code tidak ditemukan');
            return;
        }

        const svgString = new XMLSerializer().serializeToString(svgEl);
        const svgBlob = new Blob([svgString], { type: 'image/svg+xml;charset=utf-8' });
        const URL = window.URL || window.webkitURL || window;
        const blobURL = URL.createObjectURL(svgBlob);
        const img = new Image();

        img.onload = function() {
            // Draw QR Code
            ctx.drawImage(img, 175, 175, 250, 250);

            // URL Box
            ctx.fillStyle = '#0f172a';
            ctx.beginPath();
            ctx.roundRect(75, 445, 450, 45, 12);
            ctx.fill();

            ctx.fillStyle = '#67e8f9';
            ctx.font = 'bold 10px monospace';
            ctx.textAlign = 'center';
            ctx.fillText(qrUrl, 300, 472);

            // Tutorial Section Background
            ctx.fillStyle = '#fbbf24';
            ctx.fillRect(55, 530, 490, 40);
            
            // Tutorial Title
            ctx.fillStyle = '#0f172a';
            ctx.font = 'bold 16px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('CARA SCAN QR CODE', 300, 555);

            // Tutorial Steps Background
            ctx.fillStyle = '#1e293b';
            ctx.fillRect(55, 570, 490, 320);

            // Step 1
            ctx.fillStyle = '#fbbf24';
            ctx.beginPath();
            ctx.arc(85, 605, 18, 0, Math.PI * 2);
            ctx.fill();
            ctx.fillStyle = '#0f172a';
            ctx.font = 'bold 20px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('1', 85, 613);
            
            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 14px sans-serif';
            ctx.textAlign = 'left';
            ctx.fillText('Buka Aplikasi Kamera HP', 115, 600);
            ctx.fillStyle = '#94a3b8';
            ctx.font = '11px sans-serif';
            ctx.fillText('Kamera bawaan, Google Lens, atau aplikasi QR Scanner.', 115, 618);

            // Step 2
            ctx.fillStyle = '#fbbf24';
            ctx.beginPath();
            ctx.arc(85, 665, 18, 0, Math.PI * 2);
            ctx.fill();
            ctx.fillStyle = '#0f172a';
            ctx.font = 'bold 20px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('2', 85, 673);
            
            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 14px sans-serif';
            ctx.textAlign = 'left';
            ctx.fillText('Arahkan ke QR Code', 115, 660);
            ctx.fillStyle = '#94a3b8';
            ctx.font = '11px sans-serif';
            ctx.fillText('Jarak 15-20 cm, pastikan pencahayaan cukup.', 115, 678);

            // Step 3
            ctx.fillStyle = '#fbbf24';
            ctx.beginPath();
            ctx.arc(85, 725, 18, 0, Math.PI * 2);
            ctx.fill();
            ctx.fillStyle = '#0f172a';
            ctx.font = 'bold 20px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('3', 85, 733);
            
            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 14px sans-serif';
            ctx.textAlign = 'left';
            ctx.fillText('Tunggu Notifikasi Muncul', 115, 720);
            ctx.fillStyle = '#94a3b8';
            ctx.font = '11px sans-serif';
            ctx.fillText('Kamera akan mengenali QR secara otomatis.', 115, 738);

            // Step 4
            ctx.fillStyle = '#fbbf24';
            ctx.beginPath();
            ctx.arc(85, 785, 18, 0, Math.PI * 2);
            ctx.fill();
            ctx.fillStyle = '#0f172a';
            ctx.font = 'bold 20px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('4', 85, 793);
            
            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 14px sans-serif';
            ctx.textAlign = 'left';
            ctx.fillText('Tap Link untuk Membuka Menu', 115, 780);
            ctx.fillStyle = '#94a3b8';
            ctx.font = '11px sans-serif';
            ctx.fillText('Browser terbuka otomatis, mulai pesan!', 115, 798);

            // Tips Box
            ctx.fillStyle = '#065f46';
            ctx.fillRect(75, 820, 450, 60);
            
            ctx.fillStyle = '#10b981';
            ctx.font = 'bold 12px sans-serif';
            ctx.textAlign = 'left';
            ctx.fillText('💡 TIPS:', 90, 840);
            
            ctx.fillStyle = '#d1fae5';
            ctx.font = '10px sans-serif';
            ctx.fillText('• iPhone: Pakai aplikasi Camera bawaan', 90, 858);
            ctx.fillText('• Android: Pakai Google Camera atau kamera bawaan', 90, 872);

            // Download
            const pngUrl = canvas.toDataURL('image/png');
            const downloadLink = document.createElement('a');
            downloadLink.href = pngUrl;
            downloadLink.download = 'QR-Meja-' + tableNum + '.png';
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
            URL.revokeObjectURL(blobURL);
        };

        img.src = blobURL;
    }
    </script>
</div>