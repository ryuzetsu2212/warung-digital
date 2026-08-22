<div class="min-h-screen flex items-center justify-center bg-[#0c0f1a] px-4 py-12 relative overflow-hidden">
    <!-- Decorative orbs -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 left-1/4 w-80 h-80 bg-orange-600/8 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 max-w-md w-full">
        <!-- Back Button -->
        <a href="/" class="inline-flex items-center gap-1.5 text-slate-400 hover:text-slate-300 text-xs font-medium mb-8 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>

        <!-- Logo -->
        <div class="text-center mb-10">
            <div class="w-14 h-14 bg-gradient-to-br from-amber-400 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-amber-500/30">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <h1 class="text-2xl font-black text-white mb-2">Buat Akun Baru</h1>
            <p class="text-slate-400 text-sm">Daftar untuk memesan dengan mudah</p>
        </div>

        <!-- Register Card -->
        <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-2xl p-8 shadow-xl">
            <form wire:submit.prevent="register" class="space-y-5">
                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2.5">
                        Nama Lengkap
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-sm">👤</span>
                        <input 
                            type="text" 
                            id="name"
                            wire:model="name"
                            class="w-full bg-slate-950/40 border border-white/10 rounded-xl pl-11 pr-4 py-3.5 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-amber-500/50 focus:ring-2 focus:ring-amber-500/20 transition-all @error('name') border-red-500/50 @enderror"
                            placeholder="John Doe"
                            required
                        >
                    </div>
                    @error('name')
                        <p class="mt-2 text-xs text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone Field -->
                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2.5">
                        Nomor Telepon
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-sm">📱</span>
                        <input 
                            type="tel" 
                            id="phone"
                            wire:model="phone"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            class="w-full bg-slate-950/40 border border-white/10 rounded-xl pl-11 pr-4 py-3.5 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-amber-500/50 focus:ring-2 focus:ring-amber-500/20 transition-all @error('phone') border-red-500/50 @enderror"
                            placeholder="08123456789"
                            required
                            x-data
                            @input="$el.value = $el.value.replace(/[^0-9]/g, '')"
                            @keypress="if(!/[0-9]/.test($event.key)) $event.preventDefault()"
                        >
                    </div>
                    @error('phone')
                        <p class="mt-2 text-xs text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2.5">
                        Password
                    </label>
                    <div class="relative" x-data="{ show: false }">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-sm">🔒</span>
                        <input 
                            :type="show ? 'text' : 'password'"
                            id="password"
                            wire:model="password"
                            class="w-full bg-slate-950/40 border border-white/10 rounded-xl pl-11 pr-11 py-3.5 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-amber-500/50 focus:ring-2 focus:ring-amber-500/20 transition-all @error('password') border-red-500/50 @enderror"
                            placeholder="••••••••"
                            required
                        >
                        <button 
                            type="button"
                            @click="show = !show"
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-400 transition p-1"
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
                    @error('password')
                        <p class="mt-2 text-xs text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-slate-500">Minimal 8 karakter</p>
                </div>

                <!-- Password Confirmation Field -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2.5">
                        Konfirmasi Password
                    </label>
                    <div class="relative" x-data="{ show: false }">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-sm">🔒</span>
                        <input 
                            :type="show ? 'text' : 'password'"
                            id="password_confirmation"
                            wire:model="password_confirmation"
                            class="w-full bg-slate-950/40 border border-white/10 rounded-xl pl-11 pr-11 py-3.5 text-white placeholder-slate-600 text-sm focus:outline-none focus:border-amber-500/50 focus:ring-2 focus:ring-amber-500/20 transition-all"
                            placeholder="••••••••"
                            required
                        >
                        <button 
                            type="button"
                            @click="show = !show"
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-400 transition p-1"
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

                <!-- Success Message -->
                @if (session()->has('success'))
                    <div class="mb-6 bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 rounded-xl p-4 flex items-start gap-3 text-sm">
                        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Error Message -->
                @if (session()->has('error'))
                    <div class="mb-6 bg-red-500/10 border border-red-500/30 text-red-300 rounded-xl p-4 flex items-start gap-3 text-sm">
                        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Submit Button -->
                <button 
                    type="submit"
                    wire:loading.attr="disabled"
                    class="w-full mt-6 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-400 hover:to-orange-500 text-white font-bold py-3.5 px-4 rounded-xl transition shadow-lg shadow-amber-500/30 disabled:opacity-50 disabled:cursor-not-allowed text-sm tracking-wide"
                >
                    Daftar Akun
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-white/10"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="px-3 bg-white/5 text-xs text-slate-500 font-medium">Sudah punya akun?</span>
                </div>
            </div>

            <!-- Login Link -->
            <a 
                href="{{ route('customer.login') }}"
                class="block w-full text-center bg-white/10 hover:bg-white/15 text-white py-3 px-4 rounded-xl font-semibold border border-white/20 hover:border-white/30 transition-all text-sm"
            >
                Masuk Sekarang
            </a>
        </div>

    </div>
</div>
