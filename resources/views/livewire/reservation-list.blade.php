<div class="min-h-screen bg-gradient-to-b from-slate-950 to-slate-900 py-8 px-4">
    <div class="max-w-6xl mx-auto">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-amber-400">Reservasi Saya</h1>
                <p class="text-slate-400 mt-1">Kelola dan pantau reservasi meja Anda.</p>
            </div>
            <a href="{{ route('customer.reservations.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-amber-600 hover:bg-amber-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        @if(session()->has('message'))
            <div class="mb-4 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">
                {{ session('message') }}
            </div>
        @endif

        @if($reservations->isEmpty())
            <div class="bg-slate-800/50 border border-white/10 rounded-2xl shadow-xl p-8 text-center">
                <svg class="mx-auto h-16 w-16 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-2 text-lg font-medium text-white">Tidak ada reservasi</h3>
                <p class="mt-1 text-sm text-slate-400">Anda belum membuat reservasi meja.</p>
                <div class="mt-6">
                    <a href="{{ route('customer.reservations.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-amber-600 hover:bg-amber-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Buat Reservasi Baru
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($reservations as $reservation)
                    <div class="bg-slate-800/50 border border-white/10 rounded-xl p-5 shadow-lg flex flex-col justify-between hover:border-white/20 transition-all h-full">
                        <!-- Header: Info & Status -->
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-white">Meja {{ $reservation->table->nomor_meja ?? '-' }}</h3>
                                <p class="text-sm text-slate-400">{{ $reservation->customer_name }} • {{ $reservation->phone_number }}</p>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                    @if($reservation->status == 'pending') bg-amber-500/15 text-amber-300 border border-amber-500/30
                                    @elseif($reservation->status == 'confirmed') bg-emerald-500/15 text-emerald-300 border border-emerald-500/30
                                    @elseif($reservation->status == 'canceled') bg-red-500/15 text-red-300 border border-red-500/30
                                    @elseif($reservation->status == 'completed') bg-blue-500/15 text-blue-300 border border-blue-500/30
                                    @endif">
                                    @if($reservation->status == 'pending') Menunggu
                                    @elseif($reservation->status == 'confirmed') Dikonfirmasi
                                    @elseif($reservation->status == 'canceled') Dibatalkan
                                    @elseif($reservation->status == 'completed') Selesai
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Body: Details -->
                        <div class="space-y-3 mb-4 flex-1">
                            <!-- Date & Time & Guests in one row -->
                            <div class="space-y-2">
                                <div class="flex items-center text-sm text-slate-300">
                                    <svg class="h-4 w-4 text-amber-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ $reservation->reservation_date->format('d M Y') }}</span>
                                </div>
                                <div class="flex items-center text-sm text-slate-300">
                                    <svg class="h-4 w-4 text-amber-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>
                                        {{ $reservation->reservation_time->format('H:i') }}
                                        @if($reservation->reservation_end_time)
                                            - {{ \Carbon\Carbon::parse($reservation->reservation_end_time)->format('H:i') }}
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-center text-sm text-slate-300">
                                    <span class="text-amber-500 mr-2">👥</span>
                                    <span>{{ $reservation->number_of_guests ?? '-' }} orang</span>
                                </div>
                            </div>
                            
                            <!-- Order Items -->
                            <div>
                                <span class="text-slate-400 block mb-1 text-xs font-medium">Pesanan:</span>
                                @if($reservation->reservationItems->isNotEmpty())
                                    <p class="text-slate-300 text-sm" title="{{ $reservation->reservationItems->map(fn($i) => $i->qty . 'x ' . ($i->product->name ?? '')) ->join(', ') }}">
                                        {{ $reservation->reservationItems->map(fn($i) => $i->qty . 'x ' . ($i->product->name ?? ''))->join(', ') }}
                                    </p>
                                @else
                                    <span class="text-slate-500 text-sm italic">-</span>
                                @endif
                            </div>

                            <!-- Payment Info - Right after Order -->
                            <div class="space-y-2">
                                <div class="text-amber-400 font-bold text-base">
                                    Rp {{ number_format($reservation->reservationItems->sum(fn($item) => $item->price * $item->qty), 0, ',', '.') }}
                                </div>
                                <div>
                                    <span class="inline-block px-2 py-1 rounded text-xs font-medium
                                        @if($reservation->payment_status == 'pending') bg-red-500/10 text-red-400 border border-red-500/20
                                        @elseif($reservation->payment_status == 'dp_paid') bg-yellow-500/10 text-yellow-400 border border-yellow-500/20
                                        @elseif($reservation->payment_status == 'paid') bg-emerald-500/10 text-emerald-400 border border-emerald-500/20
                                        @endif">
                                        @if($reservation->payment_status == 'pending') Belum Bayar
                                        @elseif($reservation->payment_status == 'dp_paid') DP: Rp {{ number_format($reservation->dp_amount, 0, ',', '.') }}
                                        @elseif($reservation->payment_status == 'paid') Lunas
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions Footer -->
                        <div class="flex items-center gap-2 justify-end border-t border-slate-700/50 pt-4 mt-auto">
                            @if($reservation->payment_status == 'pending' && in_array($reservation->status, ['pending', 'confirmed']))
                                <a href="{{ route('customer.reservations.payment', $reservation->id) }}" class="px-4 py-2 text-sm font-semibold rounded-lg text-amber-300 bg-amber-950/40 hover:bg-amber-900/60 border border-amber-500/30 transition-colors whitespace-nowrap">
                                    💳 Bayar
                                </a>
                            @endif

                            @if($reservation->status == 'pending')
                                <button wire:click="cancelReservation({{ $reservation->id }})" wire:confirm="Yakin ingin membatalkan reservasi ini?" class="px-4 py-2 text-sm font-semibold rounded-lg text-amber-400 bg-amber-950/20 hover:bg-amber-900/40 border border-amber-500/30 transition-colors whitespace-nowrap">
                                    Batal
                                </button>
                            @endif

                            <button wire:click="deleteReservation({{ $reservation->id }})" wire:confirm="Apakah Anda yakin ingin menghapus reservasi ini dari daftar Anda?" class="px-4 py-2 text-sm font-semibold rounded-lg text-red-300 bg-red-950/40 hover:bg-red-900/60 border border-red-500/30 transition-colors whitespace-nowrap flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>