<div class="min-h-[85vh] flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full bg-slate-900/90 backdrop-blur-xl border border-slate-800 rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden">
        <!-- Glowing Accent Background -->
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

        @if(session('error'))
            <div class="mb-6 bg-red-500/10 border border-red-500/40 text-red-300 px-4 py-3 rounded-2xl text-xs sm:text-sm text-center font-medium relative z-10">
                {{ session('error') }}
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
            <a href="{{ url('/') }}" class="text-xs text-slate-400 hover:text-amber-400 font-medium transition inline-flex items-center gap-1.5">
                ← Kembali ke Beranda Pelanggan
            </a>
        </div>
    </div>
</div>