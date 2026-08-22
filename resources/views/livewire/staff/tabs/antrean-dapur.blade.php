<!-- TAB 1: ANTREAN DAPUR -->
<div x-show="tab === 'antrean'" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    
    <!-- KOLOM MAKANAN -->
    <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 shadow-xl">
        <h2 class="text-xl font-bold text-amber-400 mb-6 flex items-center justify-between border-b border-slate-700 pb-3">
            <span class="flex items-center gap-2">🍲 Antrean Makanan</span>
            <span class="text-xs bg-amber-500/20 text-amber-300 px-3 py-1 rounded-full font-semibold">
                {{ count($makananItems) }} Pending
            </span>
        </h2>

        @if($makananItems->isEmpty())
            <div class="text-center py-16 text-slate-500">
                <p class="text-3xl mb-2">✨</p>
                <p class="text-sm">Tidak ada antrean makanan saat ini.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($makananItems as $item)
                    <div class="bg-slate-700/50 border border-slate-600 rounded-2xl p-5 shadow flex flex-col justify-between gap-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="bg-indigo-600/30 text-indigo-300 text-xs font-bold px-2.5 py-1 rounded-lg">
                                    Meja {{ $item->order->table->nomor_meja }}
                                </span>
                                <h3 class="text-lg font-bold text-slate-100 mt-2">{{ $item->product->nama }}</h3>
                                <p class="text-xs text-slate-400 mt-1">
                                    Jumlah: <span class="text-white font-bold">{{ $item->qty }}</span> | 
                                    Masuk: {{ $item->created_at->format('H:i:s') }}
                                </p>
                            </div>
                            <div>
                                <span class="text-xs px-3 py-1.5 rounded-xl font-bold uppercase
                                    @if($item->status_item === 'menunggu') bg-amber-500/20 text-amber-300 border border-amber-500/30
                                    @else bg-blue-500/20 text-blue-300 border border-blue-500/30
                                    @endif">
                                    {{ ucfirst($item->status_item) }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-600">
                            @if($item->status_item === 'menunggu')
                                <button wire:click="updateItemStatus({{ $item->id }}, 'diproses')" 
                                        class="bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs px-4 py-2 rounded-xl transition shadow">
                                    Proses
                                </button>
                            @endif
                            <button wire:click="updateItemStatus({{ $item->id }}, 'selesai')" 
                                    class="bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs px-4 py-2 rounded-xl transition shadow">
                                Selesai
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- KOLOM MINUMAN -->
    <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 shadow-xl">
        <h2 class="text-xl font-bold text-cyan-400 mb-6 flex items-center justify-between border-b border-slate-700 pb-3">
            <span class="flex items-center gap-2">🥤 Antrean Minuman</span>
            <span class="text-xs bg-cyan-500/20 text-cyan-300 px-3 py-1 rounded-full font-semibold">
                {{ count($minumanItems) }} Pending
            </span>
        </h2>

        @if($minumanItems->isEmpty())
            <div class="text-center py-16 text-slate-500">
                <p class="text-3xl mb-2">✨</p>
                <p class="text-sm">Tidak ada antrean minuman saat ini.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($minumanItems as $item)
                    <div class="bg-slate-700/50 border border-slate-600 rounded-2xl p-5 shadow flex flex-col justify-between gap-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="bg-indigo-600/30 text-indigo-300 text-xs font-bold px-2.5 py-1 rounded-lg">
                                    Meja {{ $item->order->table->nomor_meja }}
                                </span>
                                <h3 class="text-lg font-bold text-slate-100 mt-2">{{ $item->product->nama }}</h3>
                                <p class="text-xs text-slate-400 mt-1">
                                    Jumlah: <span class="text-white font-bold">{{ $item->qty }}</span> | 
                                    Masuk: {{ $item->created_at->format('H:i:s') }}
                                </p>
                            </div>
                            <div>
                                <span class="text-xs px-3 py-1.5 rounded-xl font-bold uppercase
                                    @if($item->status_item === 'menunggu') bg-amber-500/20 text-amber-300 border border-amber-500/30
                                    @else bg-blue-500/20 text-blue-300 border border-blue-500/30
                                    @endif">
                                    {{ ucfirst($item->status_item) }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-600">
                            @if($item->status_item === 'menunggu')
                                <button wire:click="updateItemStatus({{ $item->id }}, 'diproses')" 
                                        class="bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs px-4 py-2 rounded-xl transition shadow">
                                    Proses
                                </button>
                            @endif
                            <button wire:click="updateItemStatus({{ $item->id }}, 'selesai')" 
                                    class="bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs px-4 py-2 rounded-xl transition shadow">
                                Selesai
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>