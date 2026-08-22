<!-- TAB 3: QR CODE MEJA -->
<div x-show="tab === 'qr'" style="display: none;">
    <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 shadow-xl">
        <div class="flex justify-between items-center border-b border-slate-700 pb-4 mb-6">
            <div>
                <h2 class="text-xl font-bold text-indigo-400 flex items-center gap-2">
                    <span>📷</span> QR Code & Link Meja
                </h2>
                <p class="text-sm text-slate-400 mt-1">Scan QR atau buka link untuk simulasi pesanan pelanggan di masing-masing meja.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($tables as $table)
                <div class="bg-slate-900 border border-slate-700 rounded-2xl p-6 shadow-xl flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold text-white">Meja {{ $table->nomor_meja }}</h3>
                            <span class="px-3 py-1 rounded-xl text-xs font-bold uppercase shadow
                                @if($table->status_meja === 'terisi') bg-amber-500/20 text-amber-300 border border-amber-500/30
                                @else bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 @endif">
                                {{ ucfirst($table->status_meja) }}
                            </span>
                        </div>

                        <div class="bg-white p-5 rounded-2xl shadow-lg flex flex-col items-center justify-center my-4">
                            <span class="text-[11px] font-bold tracking-wider uppercase text-slate-500 mb-3">Scan untuk Menu Meja {{ $table->nomor_meja }}</span>
                            <div class="w-32 h-32 flex items-center justify-center bg-white rounded-xl overflow-hidden p-1.5 border border-slate-100">
                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(128)->format('svg')->generate(route('customer.menu', $table->uuid)) !!}
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3 pt-4 border-t border-slate-800">
                        <a href="{{ route('customer.menu', $table->uuid) }}" target="_blank" 
                           class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition shadow flex items-center justify-center gap-2">
                            <span>🔗</span> Buka Link Menu
                        </a>
                        @if($table->status_meja === 'terisi')
                            <button wire:click="resetTableSession({{ $table->id }})" 
                                    class="w-full bg-red-600/20 hover:bg-red-600 text-red-300 hover:text-white border border-red-500/30 font-semibold py-2.5 px-4 rounded-xl text-xs transition">
                                🔄 Reset Sesi Meja
                            </button>
                        @else
                            <button wire:click="setTableOccupied({{ $table->id }})" 
                                    class="w-full bg-amber-600/20 hover:bg-amber-600 text-amber-300 hover:text-white border border-amber-500/30 font-semibold py-2.5 px-4 rounded-xl text-xs transition">
                                🔒 Tandai Terisi
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>