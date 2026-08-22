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
                        <span class="text-xs text-slate-400 block">Pendapatan {{ $periodLabels[$period] }}</span>
                        <span class="text-lg font-black text-amber-400">Rp {{ number_format($revenue, 0, ',', '.') }}</span>
                    </div>
                    <div class="text-right border-l border-slate-700 pl-6">
                        <span class="text-xs text-slate-400 block">Pesanan Selesai</span>
                        <span class="text-lg font-black text-emerald-400">{{ $completedCount }} Order</span>
                    </div>
                </div>
            </div>

            <!-- PERIOD FILTER TABS & ADVANCED FILTERS -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6 bg-slate-800/80 border border-slate-700 p-4 rounded-2xl">
                <div class="flex flex-wrap items-center gap-2">
                    @foreach(['today' => 'Hari Ini', 'week' => 'Minggu Ini', 'month' => 'Bulan Ini', 'year' => 'Tahun Ini'] as $key => $label)
                        <button wire:click="setPeriod('{{ $key }}')"
                                class="px-4 py-2 rounded-xl text-xs font-bold transition border
                                    @if($period === $key && empty($filterDate))
                                        bg-indigo-600 text-white border-indigo-500 shadow-lg shadow-indigo-600/30
                                    @else
                                        bg-slate-800 text-slate-300 border-slate-700 hover:bg-slate-700
                                    @endif">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <!-- Filter Tanggal -->
                    <div>
                        <input type="date" wire:model.live="filterDate"
                               class="bg-slate-900 border border-slate-700 text-slate-200 text-xs rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>

                    <!-- Filter Meja -->
                    <div>
                        <select wire:model.live="filterTable"
                                class="bg-slate-900 border border-slate-700 text-slate-200 text-xs rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="">Semua Meja</option>
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}">Meja {{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Filter Rating -->
                    <div>
                        <select wire:model.live="filterRating"
                                class="bg-slate-900 border border-slate-700 text-slate-200 text-xs rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="">Semua Rating</option>
                            <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                            <option value="4">⭐⭐⭐⭐ (4)</option>
                            <option value="3">⭐⭐⭐ (3)</option>
                            <option value="2">⭐⭐ (2)</option>
                            <option value="1">⭐ (1)</option>
                            <option value="null">Belum Ada Rating</option>
                        </select>
                    </div>

                    <!-- Sorting Total Harga -->
                    <div>
                        <select wire:model.live="sortByTotal"
                                class="bg-slate-900 border border-slate-700 text-slate-200 text-xs rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="">Urutkan: Waktu (Terbaru)</option>
                            <option value="highest">Total Harga: Tertinggi</option>
                            <option value="lowest">Total Harga: Terendah</option>
                        </select>
                    </div>

                    @if(!empty($filterDate) || !empty($filterTable) || $filterRating !== '' || !empty($sortByTotal))
                        <button wire:click="$set('filterDate', ''); $set('filterTable', ''); $set('filterRating', ''); $set('sortByTotal', '');"
                                class="text-xs text-rose-400 hover:text-rose-300 font-semibold underline px-2 py-1">
                            Reset Filter
                        </button>
                    @endif
                </div>
            </div>

            <!-- RIWAYAT PESANAN CONTENT -->
            <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 shadow-xl space-y-6">
                <div class="flex justify-between items-center border-b border-slate-700 pb-4 flex-wrap gap-3">
                    <div>
                        <h2 class="text-xl font-bold text-indigo-400 flex items-center gap-2">
                            <span>📋</span> Riwayat Pesanan Warung
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">Daftar pesanan masuk, sedang diproses, hingga selesai beserta detail item dan total pembayaran.</p>
                    </div>
                    <a href="{{ route('staff.riwayat.cetak', $period) }}" target="_blank"
                       class="bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-lg shadow-emerald-600/30 transition flex items-center gap-1.5">
                        <span>🖨️</span> Cetak PDF — {{ $periodLabels[$period] }}
                    </a>
                </div>

                @if($recentOrders->isEmpty())
                    <div class="text-center py-16 text-slate-500">
                        <p class="text-3xl mb-2">📭</p>
                        <p class="text-sm">Belum ada riwayat pesanan.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-slate-300">
                            <thead class="bg-slate-900 text-slate-400 uppercase tracking-wider text-[11px] border-b border-slate-700">
                                <tr>
                                    <th class="p-3.5">ID / Waktu</th>
                                    <th class="p-3.5">Meja & Pemesan</th>
                                    <th class="p-3.5">Detail Item</th>
                                    <th class="p-3.5">Total Harga</th>
                                    <th class="p-3.5">Status Pesanan</th>
                                    <th class="p-3.5 text-center">Rating</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-700">
                                @foreach($recentOrders as $order)
                                    @php
                                        $totalAmount = $order->calculated_total ?? 0;
                                    @endphp
                                    <tr class="hover:bg-slate-700/35 transition">
                                        <td class="p-4 font-mono align-top">
                                            <span class="font-bold text-white text-sm">#{{ $order->id }}</span>
                                            <div class="text-[10px] text-slate-400 mt-1">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                                        </td>
                                        <td class="p-4 align-top">
                                            <div class="flex flex-col gap-1.5">
                                                <span class="bg-indigo-600/30 text-indigo-300 px-2.5 py-1 rounded-lg font-bold text-xs inline-block w-fit whitespace-nowrap">
                                                    Meja {{ $order->table->nomor_meja ?? '-' }}
                                                </span>
                                                <div class="text-xs text-white font-semibold break-words">{{ $order->customer_name }}</div>
                                            </div>
                                        </td>
                                        <td class="p-4 align-top">
                                            <ul class="space-y-1.5">
                                                @foreach($order->orderItems as $oi)
                                                    <li class="text-xs">• {{ $oi->product->nama ?? 'Menu' }} (x{{ $oi->qty }}) -
                                                        <span class="text-[10px] uppercase font-semibold
                                                            @if($oi->status_item === 'selesai') text-emerald-400
                                                            @elseif($oi->status_item === 'diproses') text-blue-400
                                                            @else text-amber-400 @endif">
                                                            {{ $oi->status_item }}
                                                        </span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="p-4 font-bold text-amber-400 text-sm align-top">
                                            Rp {{ number_format($totalAmount, 0, ',', '.') }}
                                        </td>
                                        <td class="p-4 align-top">
                                            <div class="flex flex-col items-start gap-4 py-1">
                                                <span class="inline-block px-3 py-1 rounded-lg text-xs font-bold uppercase
                                                    @if($order->status === 'selesai') bg-emerald-500/20 text-emerald-300 border border-emerald-500/30
                                                    @elseif($order->status === 'diproses') bg-blue-500/20 text-blue-300 border border-blue-500/30
                                                    @elseif($order->status === 'dibatalkan') bg-red-500/20 text-red-400 border border-red-500/30
                                                    @else bg-amber-500/20 text-amber-300 border border-amber-500/30 @endif">
                                                    {{ ucfirst($order->status) }}
                                                </span>

                                                <div class="flex flex-col items-start gap-2">
                                                    @if($order->status === 'dibatalkan')
                                                        {{-- Tidak ada button untuk pesanan yang dibatalkan --}}
                                                    @elseif($order->status_pembayaran === 'lunas')
                                                        <span class="inline-block bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs px-3 py-1.5 rounded-lg font-bold uppercase">
                                                            ✅ Lunas
                                                        </span>
                                                        <div class="pt-1">
                                                            <button wire:click="cancelPayment({{ $order->id }})" class="bg-red-600 hover:bg-red-500 text-white text-xs px-4 py-2.5 rounded-xl font-bold shadow-lg transition flex items-center gap-1.5 cursor-pointer">
                                                                <span>✗</span> Batalkan Pembayaran
                                                            </button>
                                                        </div>
                                                    @elseif($order->status_pembayaran === 'menunggu_konfirmasi')
                                                        <span class="inline-block bg-amber-500/10 text-amber-400 border border-amber-500/20 text-xs px-3 py-1.5 rounded-lg font-bold uppercase animate-pulse">
                                                            ⏳ Menunggu Konfirmasi
                                                        </span>
                                                        <div class="pt-1 flex flex-col gap-2">
                                                            <button wire:click="confirmPayment({{ $order->id }})" class="bg-emerald-600 hover:bg-emerald-500 text-white text-xs px-4 py-2.5 rounded-xl font-bold shadow-lg transition flex items-center gap-1.5 cursor-pointer">
                                                                <span>✓</span> Konfirmasi Lunas
                                                            </button>
                                                        </div>
                                                    @else
                                                        <span class="inline-block bg-slate-700 text-slate-300 text-xs px-3 py-1.5 rounded-lg font-bold uppercase">
                                                            Belum Bayar
                                                        </span>
                                                        <div class="pt-1 flex flex-col gap-2">
                                                            <button wire:click="confirmPayment({{ $order->id }})" class="bg-emerald-600 hover:bg-emerald-500 text-white text-xs px-4 py-2.5 rounded-xl font-bold shadow-lg transition flex items-center gap-1.5 cursor-pointer">
                                                                <span>✓</span> Konfirmasi Lunas
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4 text-center align-top">
                                            @if($order->rating)
                                                <div class="inline-flex items-center gap-1 bg-amber-500/10 border border-amber-500/30 text-amber-300 px-2.5 py-1 rounded-lg font-bold">
                                                    <span>⭐</span> {{ $order->rating }}/5
                                                </div>
                                                @if($order->review)
                                                    <div class="text-[10px] text-slate-400 italic mt-1 max-w-xs truncate" title="{{ $order->review }}">"{{ $order->review }}"</div>
                                                @endif
                                            @else
                                                <span class="text-slate-500 text-[10px]">- Belum ada ulasan -</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>