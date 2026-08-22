<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 px-4 py-12 relative overflow-hidden">
    <!-- Decorative modern glow effects -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-amber-500/15 rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-orange-600/10 rounded-full blur-[120px]"></div>
    </div>

    <div class="relative z-10 max-w-md w-full">
        <!-- Back Button -->
        <a href="/" class="inline-flex items-center gap-2 text-slate-400 hover:text-white text-xs font-semibold mb-6 transition-colors bg-white/5 hover:bg-white/10 px-3.5 py-2 rounded-xl border border-white/10">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Beranda
        </a>

        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-tr from-amber-500 via-orange-500 to-amber-400 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl shadow-amber-500/20 ring-4 ring-white/10">
                <svg class="w-8 h-8 text-slate-950 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight mb-1.5">Warung Digital</h1>
            <p class="text-slate-400 text-sm font-medium">Masuk untuk memesan</p>
        </div>

        <!-- Login Card -->
        <div class="bg-slate-900/60 border border-white/10 backdrop-blur-2xl rounded-3xl p-8 shadow-2xl shadow-black/50">
            @if (session()->has('error'))
                <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-400 rounded-2xl p-4 flex items-start gap-3 text-sm">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if (session()->has('staffError'))
                <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-400 rounded-2xl p-4 flex items-start gap-3 text-sm">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('staffError') }}</span>
                </div>
            @endif

            <!-- Unified Login Form -->
            <form wire:submit.prevent="login" class="space-y-5">
                <!-- Email/Username Field -->
                <div>
                    <label for="identifier" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        Nama atau Nomor HP
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm">👤</span>
                        <input 
                            type="text" 
                            id="identifier"
                            wire:model="identifier"
                            class="w-full bg-slate-950/60 border border-white/10 rounded-2xl pl-11 pr-4 py-3.5 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all shadow-inner"
                            placeholder="Masukkan nama atau nomor HP"
                            required
                        >
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        Password
                    </label>
                    <div class="relative" x-data="{ show: false }">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm">🔒</span>
                        <input 
                            :type="show ? 'text' : 'password'"
                            id="password"
                            wire:model="password"
                            class="w-full bg-slate-950/60 border border-white/10 rounded-2xl pl-11 pr-12 py-3.5 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all shadow-inner"
                            placeholder="••••••••"
                            required
                        >
                        <button 
                            type="button"
                            @click="show = !show"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition p-1"
                        >
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="show" style="display: none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.07 10.07 0 014.136-5.4M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center pt-1">
                    <input 
                        type="checkbox" 
                        id="remember"
                        wire:model="remember"
                        class="w-4 h-4 accent-amber-500 border border-white/20 bg-slate-950/60 rounded cursor-pointer"
                    >
                    <label for="remember" class="ml-2.5 text-sm text-slate-300 font-medium cursor-pointer select-none">
                        Ingat saya
                    </label>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    wire:loading.attr="disabled"
                    class="w-full mt-2 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-400 hover:to-orange-500 text-slate-950 font-extrabold py-3.5 px-4 rounded-2xl transition shadow-lg shadow-amber-500/25 disabled:opacity-50 disabled:cursor-not-allowed text-sm tracking-wide active:scale-[0.98]"
                >
                    Masuk ke Akun
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-white/10"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="px-4 bg-slate-900 text-xs text-slate-400 font-medium">Belum punya akun?</span>
                </div>
            </div>

            <!-- Register Link -->
            <a 
                href="{{ route('customer.register') }}"
                class="block w-full text-center bg-white/5 hover:bg-white/10 text-white py-3.5 px-4 rounded-2xl font-bold border border-white/10 hover:border-white/20 transition-all text-sm active:scale-[0.98]"
            >
                Daftar Akun Baru
            </a>
        </div>
    </div>
</div>