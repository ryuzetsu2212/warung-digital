<div class="max-w-7xl mx-auto px-4 py-8" x-init="
    Livewire.on('session-expired', () => { window.location.reload(); });
">
    @if(!isset($isLoggedIn) || !$isLoggedIn)
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
        <!-- PENDAPATAN RESERVASI -->
        <div class="bg-slate-900/90 backdrop-blur-xl border border-slate-800/80 rounded-3xl p-6 mb-8 shadow-2xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-black text-white flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-amber-600/20 border border-amber-500/30 flex items-center justify-center text-sm">💰</span>
                    Pendapatan Reservasi
                </h3>
            </div>
            
            <!-- Filter Periode -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                <!-- Hari Ini -->
                <button wire:click="setRevenueFilter('today')" 
                        class="bg-blue-600/10 hover:bg-blue-600/20 border @if($revenueFilter == 'today') border-blue-500 @else border-blue-500/30 @endif rounded-xl p-4 transition text-left">
                    <div class="text-xs text-blue-400 font-bold mb-1">Hari Ini</div>
                    <div class="text-white font-black text-lg">Rp {{ number_format($revenueToday, 0, ',', '.') }}</div>
                    <div class="text-blue-300 text-xs mt-1">{{ $revenueTodayCount }} Order</div>
                </button>

                <!-- Minggu Ini -->
                <button wire:click="setRevenueFilter('week')" 
                        class="bg-green-600/10 hover:bg-green-600/20 border @if($revenueFilter == 'week') border-green-500 @else border-green-500/30 @endif rounded-xl p-4 transition text-left">
                    <div class="text-xs text-green-400 font-bold mb-1">Minggu Ini</div>
                    <div class="text-white font-black text-lg">Rp {{ number_format($revenueWeek, 0, ',', '.') }}</div>
                    <div class="text-green-300 text-xs mt-1">{{ $revenueWeekCount }} Order</div>
                </button>

                <!-- Bulan Ini -->
                <button wire:click="setRevenueFilter('month')" 
                        class="bg-purple-600/10 hover:bg-purple-600/20 border @if($revenueFilter == 'month') border-purple-500 @else border-purple-500/30 @endif rounded-xl p-4 transition text-left">
                    <div class="text-xs text-purple-400 font-bold mb-1">Bulan Ini</div>
                    <div class="text-white font-black text-lg">Rp {{ number_format($revenueMonth, 0, ',', '.') }}</div>
                    <div class="text-purple-300 text-xs mt-1">{{ $revenueMonthCount }} Order</div>
                </button>

                <!-- Tahun Ini -->
                <button wire:click="setRevenueFilter('year')" 
                        class="bg-orange-600/10 hover:bg-orange-600/20 border @if($revenueFilter == 'year') border-orange-500 @else border-orange-500/30 @endif rounded-xl p-4 transition text-left">
                    <div class="text-xs text-orange-400 font-bold mb-1">Tahun Ini</div>
                    <div class="text-white font-black text-lg">Rp {{ number_format($revenueYear, 0, ',', '.') }}</div>
                    <div class="text-orange-300 text-xs mt-1">{{ $revenueYearCount }} Order</div>
                </button>
            </div>
        </div>

        <!-- MAIN CONTENT - Manajemen Reservasi -->
        <div class="bg-slate-900/90 backdrop-blur-xl border border-slate-800/80 rounded-3xl p-6 sm:p-8 shadow-2xl">
            <!-- Header Section with Filter -->
            <div class="border-b border-slate-800 pb-6 mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-5 mb-6">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-black text-white flex items-center gap-3 tracking-wide">
                            <span class="w-10 h-10 rounded-2xl bg-indigo-600/20 border border-indigo-500/30 flex items-center justify-center text-lg">📅</span> 
                            Manajemen Reservasi
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-400 mt-2 font-medium">Kelola reservasi meja pelanggan secara real-time</p>
                    </div>

                    <!-- Bulk Delete Button -->
                    @if(count($selectedIds) > 0)
                        <button wire:click="bulkDelete" 
                                wire:confirm="Apakah Anda yakin ingin menghapus {{ count($selectedIds) }} reservasi terpilih?"
                                class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-lg hover:shadow-xl transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus ({{ count($selectedIds) }})
                        </button>
                    @endif
                </div>

                <!-- Filters Row -->
                <div class="flex flex-wrap items-end gap-3">
                    <!-- Status Filter -->
                    <div class="flex-1 min-w-[140px]">
                        <label class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5 block">Status</label>
                        <select wire:model.live="statusFilter"
                                class="w-full bg-slate-900 border border-slate-700 text-slate-200 text-xs rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="all">Semua Status</option>
                            <option value="pending">⏳ Menunggu</option>
                            <option value="confirmed">✓ Terkonfirmasi</option>
                            <option value="completed">✅ Selesai</option>
                            <option value="canceled">✕ Dibatalkan</option>
                        </select>
                    </div>

                    <!-- Date Filter -->
                    <div class="flex-1 min-w-[140px]">
                        <label class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5 block">Tanggal</label>
                        <input type="date" wire:model.live="filterDate"
                               class="w-full bg-slate-900 border border-slate-700 text-slate-200 text-xs rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>

                    <!-- Reset Button -->
                    <div>
                        <label class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5 block opacity-0">Reset</label>
                        <button wire:click="resetFilters"
                                class="bg-gradient-to-r from-slate-700 to-slate-600 hover:from-slate-600 hover:to-slate-500 text-white px-5 py-2 rounded-xl text-xs font-bold transition-all duration-200 flex items-center gap-2 whitespace-nowrap shadow-lg hover:shadow-xl hover:scale-105 border border-slate-600/50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Simplified Table Section -->
            <div class="overflow-x-auto rounded-2xl shadow-lg">
                <table class="w-full text-slate-200 border-collapse">
                    <thead class="bg-slate-950/80">
                        <tr>
                            <th class="px-5 py-4 text-center text-xs font-bold text-slate-400 uppercase tracking-wider w-12">
                                <input type="checkbox" wire:model.live="selectAll"
                                       class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-indigo-600 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-0 cursor-pointer">
                            </th>
                            <th class="px-5 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Meja</th>
                            <th class="px-5 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-5 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Kontak</th>
                            <th class="px-5 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Jadwal</th>
                             <th class="px-5 py-4 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Status</th>
                             <th class="px-5 py-4 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Pembayaran</th>
                             <th class="px-5 py-4 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-slate-900/50">
                        @forelse($reservations as $reservation)
                            <tr class="hover:bg-slate-800/40 transition-colors border-b border-slate-800/50" wire:key="reservation-{{ $reservation->id }}">
                                <td class="px-5 py-4 text-center">
                                    @if(in_array($reservation->status, ['pending', 'canceled']))
                                        <input type="checkbox" wire:model.live="selectedIds" value="{{ $reservation->id }}"
                                               class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-indigo-600 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-0 cursor-pointer">
                                    @else
                                        <span class="text-slate-600 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-1.5 bg-slate-800 text-slate-200 px-3 py-1.5 rounded-xl text-xs font-bold">Meja {{ $reservation->table->nomor_meja ?? '-' }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-white text-sm">{{ $reservation->customer_name }}</div>
                                </td>
                                <td class="px-5 py-4 text-sm text-slate-400">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-slate-500">📞</span>
                                        {{ $reservation->phone_number }}
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="text-sm font-bold text-white flex items-center gap-1.5">
                                        <span class="text-slate-500">📅</span>
                                        {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-indigo-400 font-semibold flex items-center gap-1.5 mt-1">
                                        <span>⏰</span>
                                        {{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }}
                                        @if($reservation->reservation_end_time)
                                            - {{ \Carbon\Carbon::parse($reservation->reservation_end_time)->format('H:i') }}
                                        @endif
                                    </div>
                                </td>
                                 <td class="px-5 py-4 text-center">
                                     <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold uppercase rounded-full whitespace-nowrap
                                         @if($reservation->status == 'pending') bg-amber-500/15 text-amber-300 border border-amber-500/30
                                         @elseif($reservation->status == 'confirmed') bg-blue-500/15 text-blue-300 border border-blue-500/30
                                         @elseif($reservation->status == 'completed') bg-emerald-500/15 text-emerald-300 border border-emerald-500/30
                                         @else bg-red-500/15 text-red-300 border border-red-500/30
                                         @endif">
                                         @if($reservation->status == 'completed') 
                                             ✅ Selesai
                                         @elseif($reservation->status == 'canceled') 
                                             ✕ Dibatalkan
                                         @elseif($reservation->status == 'confirmed') 
                                             ✓ Terkonfirmasi
                                         @else 
                                             ⏳ Menunggu
                                         @endif
                                     </span>
                                 </td>
                                 <td class="px-5 py-4 text-center">
                                     <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold uppercase rounded-full whitespace-nowrap
                                         @if($reservation->payment_status == 'pending') bg-red-500/15 text-red-300 border border-red-500/30
                                         @elseif($reservation->payment_status == 'dp_paid') bg-yellow-500/15 text-yellow-300 border border-yellow-500/30
                                         @elseif($reservation->payment_status == 'paid') bg-emerald-500/15 text-emerald-300 border border-emerald-500/30
                                         @endif">
                                         @if($reservation->payment_status == 'pending') 
                                             ❌ Belum
                                         @elseif($reservation->payment_status == 'dp_paid') 
                                             🟡 DP 50% Sudah Dibayar
                                         @elseif($reservation->payment_status == 'paid')
                                             🟢 Lunas
                                         @endif
                                     </span>
                                 </td>
                                  <td class="px-5 py-4">
                                     <div class="flex items-center justify-center gap-2 flex-wrap">
                                         <button wire:click="showDetail({{ $reservation->id }})"
                                             class="text-xs bg-slate-700 hover:bg-slate-600 text-white px-3 py-2 rounded-xl transition font-bold whitespace-nowrap shadow-md hover:shadow-lg flex items-center gap-1.5">
                                             <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                             </svg>
                                             Detail
                                         </button>
                                         
                                          @if($reservation->status == 'pending')
                                              <button wire:click="updateStatus({{ $reservation->id }}, 'confirmed')"
                                                  class="text-xs bg-indigo-600 hover:bg-indigo-500 text-white px-3 py-2 rounded-xl transition font-bold whitespace-nowrap shadow-md hover:shadow-lg flex items-center gap-1.5">
                                                  <span>✓</span> Konfirmasi
                                              </button>
                                              <button wire:click="updateStatus({{ $reservation->id }}, 'canceled')"
                                                  class="text-xs bg-red-600 hover:bg-red-500 text-white px-3 py-2 rounded-xl transition font-bold whitespace-nowrap shadow-md hover:shadow-lg flex items-center gap-1.5">
                                                  <span>✕</span> Batalkan
                                              </button>
                                          @elseif($reservation->status == 'confirmed')
                                              <button wire:click="updateStatus({{ $reservation->id }}, 'completed')"
                                                  class="text-xs bg-emerald-600 hover:bg-emerald-500 text-white px-3 py-2 rounded-xl transition font-bold whitespace-nowrap shadow-md hover:shadow-lg flex items-center gap-1.5">
                                                  <span>✓</span> Selesai
                                              </button>
                                              <button wire:click="updateStatus({{ $reservation->id }}, 'canceled')"
                                                  class="text-xs bg-red-600 hover:bg-red-500 text-white px-3 py-2 rounded-xl transition font-bold whitespace-nowrap shadow-md hover:shadow-lg flex items-center gap-1.5">
                                                  <span>✕</span> Batalkan
                                              </button>
                                          @endif
                                     </div>
                                  </td>
                            </tr>
                        @empty
                            <tr>
                                 <td colspan="7" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="w-20 h-20 rounded-full bg-slate-800/50 flex items-center justify-center text-5xl">
                                            📅
                                        </div>
                                        <div>
                                            <p class="text-slate-300 font-bold text-base mb-1">Belum ada reservasi masuk</p>
                                            <p class="text-xs text-slate-500">Reservasi pelanggan akan muncul di sini</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(method_exists($reservations, 'hasPages') && $reservations->hasPages())
                <div class="mt-8 pt-6 border-t border-slate-800">
                    {{ $reservations->links() }}
                </div>
            @endif
        </div>

        <!-- Detail Modal -->
        @if($showDetailModal && $selectedReservation)
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-start justify-center p-4 overflow-y-auto" 
                 wire:click="closeDetail"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-init="document.body.style.overflow = 'hidden'"
                 x-effect="if (!$wire.showDetailModal) document.body.style.overflow = 'auto'">
                <div class="bg-slate-900 border border-slate-700 rounded-3xl shadow-2xl max-w-md w-full flex flex-col my-8"
                     wire:click.stop>
                    
                    <!-- Modal Header -->
                    <div class="bg-slate-900 border-b border-slate-800 px-5 py-4 flex items-center justify-between flex-shrink-0 rounded-t-3xl">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-xl bg-indigo-600/20 border border-indigo-500/30 flex items-center justify-center text-base">📋</span>
                            <div>
                                <h3 class="text-base font-black text-white">Detail Reservasi</h3>
                                <p class="text-[10px] text-slate-400">#{{ str_pad($selectedReservation->id, 4, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                        <button wire:click="closeDetail" 
                                class="text-slate-400 hover:text-white transition p-1.5 hover:bg-slate-800 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Content (Scrollable Container) -->
                    <div class="p-5 space-y-4 overflow-y-auto max-h-[calc(85vh-110px)]">
                        <!-- Status Badge -->
                        <div class="flex justify-center">
                            <span class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold uppercase rounded-2xl
                                @if($selectedReservation->status == 'pending') bg-amber-500/15 text-amber-300 border-2 border-amber-500/30
                                @elseif($selectedReservation->status == 'confirmed') bg-blue-500/15 text-blue-300 border-2 border-blue-500/30
                                @elseif($selectedReservation->status == 'completed') bg-emerald-500/15 text-emerald-300 border-2 border-emerald-500/30
                                @else bg-red-500/15 text-red-300 border-2 border-red-500/30
                                @endif">
                                @if($selectedReservation->status == 'completed') 
                                    <span class="text-xl">✅</span> Selesai
                                @elseif($selectedReservation->status == 'canceled') 
                                    <span class="text-xl">✕</span> Dibatalkan
                                @elseif($selectedReservation->status == 'confirmed') 
                                    <span class="text-xl">✓</span> Terkonfirmasi
                                @else 
                                    <span class="text-xl">⏳</span> Menunggu
                                @endif
                            </span>
                        </div>

                         <!-- Info Grid -->
                         <div class="grid grid-cols-2 gap-2">
                             <!-- Meja -->
                             <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-2">
                                 <div class="text-[10px] text-slate-400 uppercase tracking-wider mb-0.5 font-bold">Meja</div>
                                 <div class="text-xs font-bold text-white">Meja {{ $selectedReservation->table->nomor_meja ?? '-' }}</div>
                             </div>

                             <!-- Pelanggan -->
                             <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-2">
                                 <div class="text-[10px] text-slate-400 uppercase tracking-wider mb-0.5 font-bold">Pelanggan</div>
                                 <div class="text-xs font-bold text-white truncate">{{ $selectedReservation->customer_name }}</div>
                             </div>

                             <!-- Kontak -->
                             <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-2">
                                 <div class="text-[10px] text-slate-400 uppercase tracking-wider mb-0.5 font-bold">Kontak</div>
                                 <div class="text-xs font-bold text-white">{{ $selectedReservation->phone_number }}</div>
                             </div>

                             <!-- Jumlah Tamu -->
                             <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-2">
                                 <div class="text-[10px] text-slate-400 uppercase tracking-wider mb-0.5 font-bold">Tamu</div>
                                 <div class="text-xs font-bold text-white">{{ $selectedReservation->number_of_guests }} org</div>
                             </div>

                             <!-- Tanggal -->
                             <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-2">
                                 <div class="text-[10px] text-slate-400 uppercase tracking-wider mb-0.5 font-bold">Tanggal</div>
                                 <div class="text-xs font-bold text-white">{{ \Carbon\Carbon::parse($selectedReservation->reservation_date)->format('d M Y') }}</div>
                             </div>

                             <!-- Waktu -->
                             <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-2">
                                 <div class="text-[10px] text-slate-400 uppercase tracking-wider mb-0.5 font-bold">Waktu</div>
                                 <div class="text-xs font-bold text-indigo-400">
                                     {{ \Carbon\Carbon::parse($selectedReservation->reservation_time)->format('H:i') }}
                                     @if($selectedReservation->reservation_end_time)
                                         - {{ \Carbon\Carbon::parse($selectedReservation->reservation_end_time)->format('H:i') }}
                                     @endif
                                 </div>
                             </div>
                         </div>

                         <!-- Item Pesanan & Pembayaran -->
                         <div class="grid grid-cols-1 gap-1.5">
                             <!-- Item Pesanan -->
                             @if($selectedReservation->reservationItems && $selectedReservation->reservationItems->count() > 0)
                                 <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-2">
                                     <div class="text-[10px] text-slate-400 uppercase tracking-wider font-bold mb-1.5">🍽️ Item Pesanan</div>
                                     <div class="space-y-1.5">
                                         @foreach($selectedReservation->reservationItems as $item)
                                             <div class="flex items-center justify-between bg-slate-900/50 px-2 py-1 rounded-lg border border-slate-700">
                                                 <div class="flex items-center gap-2">
                                                     <span class="text-slate-400 font-bold text-xs">{{ $item->qty }}x</span>
                                                     <span class="text-white font-semibold text-xs">{{ $item->product->nama ?? 'Produk' }}</span>
                                                 </div>
                                                 <span class="text-amber-400 font-bold text-xs">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                             </div>
                                         @endforeach
                                         <div class="flex items-center justify-between bg-emerald-500/10 px-2 py-1.5 rounded-lg border border-emerald-500/30 mt-2">
                                             <span class="text-emerald-300 font-bold text-xs">💰 Total</span>
                                             <span class="text-emerald-400 font-black text-xs">
                                                 Rp {{ number_format($selectedReservation->reservationItems->sum(function($item) { return $item->qty * $item->price; }), 0, ',', '.') }}
                                             </span>
                                         </div>
                                     </div>
                                 </div>
                             @else
                                 <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-3 text-center">
                                     <span class="text-slate-500 italic text-xs">Belum ada item pesanan</span>
                                 </div>
                             @endif

                             <!-- Status Pembayaran & Aksi -->
                             <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-3">
                                 <div class="text-[10px] text-slate-400 uppercase tracking-wider font-bold mb-2">💳 Status Pembayaran</div>
                                 <div class="space-y-2">
                                     <!-- Total & DP -->
                                     <div class="space-y-1">
                                         <div class="flex justify-between px-2 py-1 bg-slate-900/50 rounded-lg border border-slate-700">
                                             <span class="text-slate-400 text-[10px]">Total Tagihan</span>
                                             <span class="text-white font-bold text-xs">Rp {{ number_format($selectedReservation->total_amount, 0, ',', '.') }}</span>
                                         </div>
                                         <div class="flex justify-between px-2 py-1 bg-slate-900/50 rounded-lg border border-slate-700">
                                             <span class="text-slate-400 text-[10px]">DP Minimal (50%)</span>
                                             <span class="text-amber-400 font-bold text-xs">Rp {{ number_format($selectedReservation->dp_amount, 0, ',', '.') }}</span>
                                         </div>
                                     </div>

                                      <!-- Status Badge -->
                                      <div class="pt-1 border-t border-slate-700">
                                          <span class="text-[9px] text-slate-400 block mb-1">Status Saat Ini</span>
                                          <span class="inline-flex items-center gap-1 px-2 py-1 text-[10px] font-bold uppercase rounded-lg
                                              @if($selectedReservation->payment_status == 'pending') bg-red-500/15 text-red-300 border border-red-500/30
                                              @elseif($selectedReservation->payment_status == 'dp_pending') bg-amber-500/15 text-amber-300 border border-amber-500/30
                                              @elseif($selectedReservation->payment_status == 'dp_paid') bg-yellow-500/15 text-yellow-300 border border-yellow-500/30
                                              @elseif($selectedReservation->payment_status == 'paid') bg-emerald-500/15 text-emerald-300 border border-emerald-500/30
                                              @endif">
                                              @if($selectedReservation->payment_status == 'pending') 
                                                  ❌ Belum Bayar
                                              @elseif($selectedReservation->payment_status == 'dp_pending') 
                                                  ⏳ Menunggu Konfirmasi
                                              @elseif($selectedReservation->payment_status == 'dp_paid') 
                                                  🟡 DP 50% Sudah Dibayar
                                              @elseif($selectedReservation->payment_status == 'paid')
                                                   Lunas
                                              @endif
                                          </span>
                                      </div>

                                      <!-- Payment Action Buttons -->
                                      <div class="flex flex-wrap gap-2 mt-2">
                                          @if($selectedReservation->payment_status == 'dp_pending')
                                              <button wire:click="confirmDpPayment({{ $selectedReservation->id }})"
                                                      class="flex-1 min-w-[100px] bg-green-600 hover:bg-green-500 text-white px-3 py-2 rounded-lg transition font-bold shadow-lg hover:shadow-xl flex items-center justify-center gap-1 text-xs"
                                                      wire:confirm="Konfirmasi pembayaran DP 50%? Reservasi akan segera diaktifkan.">
                                                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                  </svg>
                                                  Konfirmasi DP
                                              </button>
                                          @elseif($selectedReservation->payment_status == 'dp_paid')
                                              <button wire:click="updatePaymentStatus({{ $selectedReservation->id }}, 'paid')"
                                                      class="flex-1 min-w-[100px] bg-emerald-600 hover:bg-emerald-500 text-white px-3 py-2 rounded-lg transition font-bold shadow-lg hover:shadow-xl flex items-center justify-center gap-1 text-xs"
                                                      wire:confirm="Apakah Anda yakin ingin menandai reservasi ini sebagai Lunas?">
                                                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                  </svg>
                                                  Lunas
                                              </button>
                                          @endif
                                      </div>

                                     <!-- Bukti Pembayaran -->
                                     @if($selectedReservation->payment_proof)
                                         <div class="pt-2 border-t border-slate-700">
                                             <span class="text-[9px] text-slate-400 block mb-1.5">📸 Bukti Pembayaran</span>
                                             <a href="{{ asset('storage/' . $selectedReservation->payment_proof) }}" target="_blank"
                                                class="block w-full">
                                                 <img src="{{ asset('storage/' . $selectedReservation->payment_proof) }}" 
                                                      alt="Bukti Pembayaran" 
                                                      class="w-full h-auto rounded-lg border-2 border-slate-700 hover:border-indigo-500 transition cursor-pointer">
                                             </a>
                                         </div>
                                     @endif

                                     <!-- Quick Action Buttons -->
                                  <!-- Removed Manual Payment Buttons -->
                                 </div>
                             </div>
                         </div>

                        <!-- Catatan -->
                        @if($selectedReservation->notes)
                            <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-3">
                                <div class="text-[10px] text-slate-400 uppercase tracking-wider font-bold mb-2">📝 Catatan</div>
                                <p class="text-slate-300 leading-relaxed text-xs">{{ $selectedReservation->notes }}</p>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-2 pt-2 border-t border-slate-800">
                            @if($selectedReservation->status == 'pending')
                                <button wire:click="updateStatus({{ $selectedReservation->id }}, 'confirmed')"
                                    class="flex-1 min-w-[100px] bg-indigo-600 hover:bg-indigo-500 text-white px-3 py-2 rounded-lg transition font-bold shadow-lg hover:shadow-xl flex items-center justify-center gap-1 text-xs">
                                    ✓ Konfirmasi
                                </button>
                                <button wire:click="updateStatus({{ $selectedReservation->id }}, 'canceled')"
                                    class="flex-1 min-w-[100px] bg-red-600 hover:bg-red-500 text-white px-3 py-2 rounded-lg transition font-bold shadow-lg hover:shadow-xl flex items-center justify-center gap-1 text-xs">
                                    ✕ Batalkan
                                </button>
                            @elseif($selectedReservation->status == 'confirmed')
                                <button wire:click="updateStatus({{ $selectedReservation->id }}, 'completed')"
                                    class="flex-1 min-w-[100px] bg-emerald-600 hover:bg-emerald-500 text-white px-3 py-2 rounded-lg transition font-bold shadow-lg hover:shadow-xl flex items-center justify-center gap-1 text-xs">
                                    ✓ Selesai
                                </button>
                                <button wire:click="updateStatus({{ $selectedReservation->id }}, 'canceled')"
                                    class="flex-1 min-w-[100px] bg-red-600 hover:bg-red-500 text-white px-3 py-2 rounded-lg transition font-bold shadow-lg hover:shadow-xl flex items-center justify-center gap-1 text-xs">
                                    ✕ Batalkan
                                </button>
                            @elseif($selectedReservation->status == 'completed')
                                <button wire:click="updateStatus({{ $selectedReservation->id }}, 'canceled')"
                                    class="flex-1 bg-red-600 hover:bg-red-500 text-white px-3 py-2 rounded-lg transition font-bold shadow-lg hover:shadow-xl flex items-center justify-center gap-1 text-xs">
                                    ✕ Batalkan
                                </button>
                            @elseif($selectedReservation->status == 'canceled')
                                <button wire:click="updateStatus({{ $selectedReservation->id }}, 'confirmed')"
                                    class="flex-1 bg-indigo-600 hover:bg-indigo-500 text-white px-3 py-2 rounded-lg transition font-bold shadow-lg hover:shadow-xl flex items-center justify-center gap-1 text-xs">
                                    ↻ Konfirmasi Ulang
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>