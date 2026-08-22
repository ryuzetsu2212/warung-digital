<!-- TAB 4: RIWAYAT PESANAN -->
<div x-show="tab === 'riwayat'" style="display: none;" class="bg-slate-800 border border-slate-700 rounded-2xl p-6 shadow-xl space-y-6">
    <div class="flex justify-between items-center border-b border-slate-700 pb-4">
        <div>
            <h2 class="text-xl font-bold text-emerald-400 flex items-center gap-2">
                <span>📋</span> Riwayat Pesanan Terbaru
            </h2>
            <p class="text-xs text-slate-400 mt-1">Daftar pesanan masuk dan status terakhir di seluruh meja</p>
        </div>
    </div>

    <!-- Filter Controls -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div>
            <label class="block text-xs font-bold text-slate-400 mb-1">Periode Pendapatan</label>
            <select wire:model.live="revenueFilter" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-indigo-500">
                <option value="hari ini">Hari Ini</option>
                <option value="minggu ini">Minggu Ini</option>
                <option value="bulan ini">Bulan Ini</option>
                <option value="tahun ini">Tahun Ini</option>
            </select>
        </div>
        
        <div>
            <label class="block text-xs font-bold text-slate-400 mb-1">Filter Meja</label>
            <select wire:model.live="tableFilter" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-indigo-500">
                <option value="">Semua Meja</option>
                @foreach($tables as $table)
                    <option value="{{ $table->nomor_meja }}">Meja {{ $table->nomor_meja }}</option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label class="block text-xs font-bold text-slate-400 mb-1">Filter Tanggal</label>
            <input type="date" wire:model.live="dateFilter" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-indigo-500">
        </div>
        
        <div>
            <label class="block text-xs font-bold text-slate-400 mb-1">Filter Rating</label>
            <select wire:model.live="ratingFilter" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-indigo-500">
                <option value="">Semua Rating</option>
                <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                <option value="4">⭐⭐⭐⭐ (4)</option>
                <option value="3">⭐⭐⭐ (3)</option>
                <option value="2">⭐⭐ (2)</option>
                <option value="1">⭐ (1)</option>
            </select>
        </div>
    </div>

    @if($recentOrders->isEmpty())
        <div class="text-center py-16 text-slate-500">
            <p class="text-3xl mb-2">📂</p>
            <p class="text-sm">Belum ada riwayat pesanan.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-700 text-xs font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3 px-4">ID Pesanan</th>
                        <th class="py-3 px-4">Meja</th>
                        <th class="py-3 px-4">Daftar Item</th>
                        <th class="py-3 px-4">Status Utama</th>
                        <th class="py-3 px-4">Waktu</th>
                        <th class="py-3 px-4">Rating</th>
                        <th class="py-3 px-4">Ulasan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50 text-sm">
                    @foreach($recentOrders as $ord)
                        <tr class="hover:bg-slate-700/20 transition">
                            <td class="py-3.5 px-4 font-bold text-indigo-400">#{{ $ord->id }}</td>
                            <td class="py-3.5 px-4">
                                <span class="bg-slate-700 text-slate-200 px-2.5 py-1 rounded-lg text-xs font-semibold">
                                    Meja {{ $ord->table->nomor_meja ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="space-y-1">
                                    @foreach($ord->orderItems as $i)
                                        <div class="text-xs text-slate-300">
                                            • {{ $i->product->nama ?? 'Produk' }} (x{{ $i->qty }})
                                            <span class="text-[10px] px-1.5 py-0.5 rounded ml-1
                                                @if($i->status_item === 'selesai') bg-emerald-500/20 text-emerald-300
                                                @elseif($i->status_item === 'diproses') bg-blue-500/20 text-blue-300
                                                @elseif($i->status_item === 'dibatalkan') bg-red-500/20 text-red-300
                                                @else bg-amber-500/20 text-amber-300 @endif">
                                                {{ $i->status_item }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="text-xs px-3 py-1 rounded-full font-bold uppercase
                                    @if($ord->status === 'selesai') bg-emerald-500/20 text-emerald-300 border border-emerald-500/30
                                    @elseif($ord->status === 'diproses') bg-blue-500/20 text-blue-300 border border-blue-500/30
                                    @elseif($ord->status === 'dibatalkan') bg-red-500/20 text-red-300 border border-red-500/30
                                    @else bg-amber-500/20 text-amber-300 border border-amber-500/30 @endif">
                                    {{ ucfirst($ord->status) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-400">
                                <div>{{ $ord->created_at->format('d M Y, H:i') }}</div>
                                <div class="text-[10px] text-indigo-400 font-semibold mt-0.5">{{ $ord->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-amber-400 font-bold">
                                @if($ord->rating)
                                    {{ $ord->rating }} ⭐
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-400">
                                {{ $ord->review ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>