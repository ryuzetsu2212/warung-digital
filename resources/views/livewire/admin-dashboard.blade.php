<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950">
    @if(!$isLoggedIn)
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <p class="text-slate-400">Redirecting...</p>
        </div>
    </div>
    @else
    <!-- Header with Responsive Dropdown -->
    <header class="bg-slate-900/80 backdrop-blur-xl border-b border-slate-800 sticky top-0 z-40 shadow-2xl" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4 cursor-pointer" title="Refresh Dashboard">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-600 to-indigo-600 flex items-center justify-center text-2xl shadow-lg">
                        👑
                    </div>
                    <div>
                        <h1 class="text-xl font-black text-white tracking-wide">Admin Dashboard</h1>
                        <p class="text-xs text-slate-400">Warung Digital Management System</p>
                    </div>
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-3">
                    <a href="{{ route('admin.profile') }}"
                        class="bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white px-4 py-2 rounded-xl text-sm font-bold transition shadow-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profil
                    </a>
                    <button wire:click="logout"
                        class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-xl text-sm font-bold transition shadow-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Keluar
                    </button>
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                    class="md:hidden bg-slate-800 hover:bg-slate-700 text-white p-2 rounded-xl transition shadow-lg">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Mobile Dropdown Menu -->
            <div x-show="mobileMenuOpen" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                @click.away="mobileMenuOpen = false"
                class="md:hidden mt-4 space-y-2"
                style="display: none;"
                x-data="{ active: @entangle('activeTab') }">
                
                <!-- Navigation Menu Items -->
                <button wire:click="switchTab('statistik'); mobileMenuOpen = false"
                    :class="active === 'statistik' ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-400'"
                    class="px-4 py-3 rounded-xl font-bold text-sm transition shadow-lg flex items-center gap-2 w-full hover:text-white">
                    <span class="text-lg">📊</span> Dashboard
                </button>
                <button wire:click="switchTab('staff'); mobileMenuOpen = false"
                    :class="active === 'staff' ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-400'"
                    class="px-4 py-3 rounded-xl font-bold text-sm transition shadow-lg flex items-center gap-2 w-full hover:text-white">
                    <span class="text-lg">👥</span> Manajemen Staff
                </button>
                <button wire:click="switchTab('customers'); mobileMenuOpen = false"
                    :class="active === 'customers' ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-400'"
                    class="px-4 py-3 rounded-xl font-bold text-sm transition shadow-lg flex items-center gap-2 w-full hover:text-white">
                    <span class="text-lg">🙍</span> Data Tamu
                </button>
                <button wire:click="switchTab('reports'); mobileMenuOpen = false"
                    :class="active === 'reports' ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-400'"
                    class="px-4 py-3 rounded-xl font-bold text-sm transition shadow-lg flex items-center gap-2 w-full hover:text-white">
                    <span class="text-lg">📈</span> Laporan
                </button>
                
                <!-- Divider -->
                <div class="border-t border-slate-700 my-2"></div>
                
                <!-- Profile & Logout -->
                <a href="{{ route('admin.profile') }}"
                    class="bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white px-4 py-3 rounded-xl text-sm font-bold transition shadow-lg flex items-center gap-2 w-full">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profil
                </a>
                <button wire:click="logout"
                    class="bg-red-600 hover:bg-red-500 text-white px-4 py-3 rounded-xl text-sm font-bold transition shadow-lg flex items-center gap-2 w-full">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Keluar
                </button>
            </div>
        </div>
    </header>

    <!-- Navigation Tabs - Desktop Only -->
    <div class="bg-slate-900/60 border-b border-slate-800 hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex gap-2 overflow-x-auto py-4" x-data="{ active: @entangle('activeTab') }">
                <button wire:click="switchTab('statistik')"
                    :class="active === 'statistik' ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-400 hover:text-white'"
                    class="px-6 py-3 rounded-xl font-bold text-sm whitespace-nowrap transition flex items-center gap-2 shadow-lg">
                    <span class="text-lg">📊</span> Dashboard
                </button>
                <button wire:click="switchTab('staff')"
                    :class="active === 'staff' ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-400 hover:text-white'"
                    class="px-6 py-3 rounded-xl font-bold text-sm whitespace-nowrap transition flex items-center gap-2 shadow-lg">
                    <span class="text-lg">👥</span> Manajemen Staff
                </button>
                <button wire:click="switchTab('customers')"
                    :class="active === 'customers' ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-400 hover:text-white'"
                    class="px-6 py-3 rounded-xl font-bold text-sm whitespace-nowrap transition flex items-center gap-2 shadow-lg">
                    <span class="text-lg">🙍</span> Data Tamu
                </button>
                <button wire:click="switchTab('reports')"
                    :class="active === 'reports' ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-400 hover:text-white'"
                    class="px-6 py-3 rounded-xl font-bold text-sm whitespace-nowrap transition flex items-center gap-2 shadow-lg">
                    <span class="text-lg">📈</span> Laporan
                </button>
            </nav>
        </div>
    </div>

    <!-- Content Area -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session()->has('message'))
        <div class="mb-6 bg-emerald-500/10 border border-emerald-500/40 text-emerald-300 px-4 py-3 rounded-2xl text-sm animate-pulse">
            {{ session('message') }}
        </div>
        @endif

        <!-- Tab: Statistik (tidak berubah) -->
        @if($activeTab === 'statistik')
        <div class="space-y-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Staff -->
                <div class="bg-gradient-to-br from-blue-600/20 to-blue-900/20 border border-blue-500/30 rounded-3xl p-6 shadow-xl">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 rounded-2xl bg-blue-600/30 flex items-center justify-center text-3xl">👥</div>
                        <span class="text-blue-400 text-xs font-bold uppercase">Staff</span>
                    </div>
                    <div class="text-4xl font-black text-white mb-1">{{ $totalStaff }}</div>
                    <div class="text-xs text-blue-300">Total Staff Aktif</div>
                </div>
                <!-- Total Customers -->
                <div class="bg-gradient-to-br from-purple-600/20 to-purple-900/20 border border-purple-500/30 rounded-3xl p-6 shadow-xl">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 rounded-2xl bg-purple-600/30 flex items-center justify-center text-3xl">🙍</div>
                        <span class="text-purple-400 text-xs font-bold uppercase">Tamu</span>
                    </div>
                    <div class="text-4xl font-black text-white mb-1">{{ $totalCustomers }}</div>
                    <div class="text-xs text-purple-300">Total Tamu Terdaftar</div>
                </div>
                <!-- Today Revenue -->
                <div class="bg-gradient-to-br from-emerald-600/20 to-emerald-900/20 border border-emerald-500/30 rounded-3xl p-6 shadow-xl">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-600/30 flex items-center justify-center text-3xl">💰</div>
                        <span class="text-emerald-400 text-xs font-bold uppercase">Pendapatan</span>
                    </div>
                    <div class="text-2xl font-black text-white mb-1">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</div>
                    <div class="text-xs text-emerald-300">Pendapatan Hari Ini</div>
                </div>
                <!-- Active Reservations -->
                <div class="bg-gradient-to-br from-amber-600/20 to-amber-900/20 border border-amber-500/30 rounded-3xl p-6 shadow-xl">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 rounded-2xl bg-amber-600/30 flex items-center justify-center text-3xl">📅</div>
                        <span class="text-amber-400 text-xs font-bold uppercase">Reservasi</span>
                    </div>
                    <div class="text-4xl font-black text-white mb-1">{{ $activeReservations }}</div>
                    <div class="text-xs text-amber-300">Reservasi Aktif</div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-xl">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <span class="text-2xl">📦</span> Pesanan Hari Ini
                    </h3>
                    <div class="text-5xl font-black text-indigo-400">{{ $todayOrders }}</div>
                    <p class="text-sm text-slate-400 mt-2">Total pesanan masuk hari ini</p>
                </div>
                <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-xl">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <span class="text-2xl">🪑</span> Meja Tersedia
                    </h3>
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <div class="text-4xl font-black text-emerald-400">{{ $availableTables }}</div>
                            <p class="text-xs text-slate-400 mt-1">dari {{ $totalTables }} meja</p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-slate-500">{{ $occupiedTables }}</div>
                            <p class="text-xs text-slate-500">Terisi</p>
                        </div>
                    </div>
                    <div class="mt-4 bg-slate-800/50 rounded-lg p-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-400">Kapasitas</span>
                            <span class="text-slate-300 font-semibold">{{ round(($occupiedTables / $totalTables) * 100) }}%</span>
                        </div>
                        <div class="mt-1 h-2 bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-emerald-500 to-emerald-400 transition-all duration-500" 
                                 style="width: {{ ($occupiedTables / $totalTables) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-xl">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <span class="text-2xl">🕐</span> Waktu Operasional
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-400">Status Warung</span>
                            <span class="{{ $isOpen ? 'text-emerald-400' : 'text-red-400' }} text-sm font-bold flex items-center gap-1">
                                <span class="w-2 h-2 {{ $isOpen ? 'bg-emerald-400' : 'bg-red-400' }} rounded-full animate-pulse"></span> 
                                {{ $operationalStatus }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-400">Jam Buka</span>
                            <span class="text-slate-300 text-xs font-semibold">07:00 - 17:00</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-400">Jam Malam</span>
                            <span class="text-slate-300 text-xs font-semibold">19:00 - 23:00</span>
                        </div>
                        <div class="mt-3 bg-slate-800/50 rounded-lg p-3 text-center">
                            <p class="text-xs text-slate-400">{{ $operationalTime }}</p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-800">
                            <button wire:click="toggleOperationalStatus" 
                                wire:confirm="Apakah Anda yakin ingin {{ $isOpen ? 'menutup' : 'membuka' }} warung secara manual?"
                                class="w-full {{ $isOpen ? 'bg-red-600 hover:bg-red-500' : 'bg-emerald-600 hover:bg-emerald-500' }} text-white px-4 py-2.5 rounded-xl text-sm font-bold transition flex items-center justify-center gap-2 shadow-lg">
                                @if($isOpen)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                    </svg>
                                    Tutup Warung
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Buka Warung
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Tab: Staff Management (tidak berubah) -->
        @if($activeTab === 'staff')
        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-black text-white">Manajemen Staff</h2>
                <button wire:click="openStaffModal"
                    class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Staff
                </button>
            </div>

            <div class="bg-slate-900/90 border border-slate-800 rounded-3xl shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-max">
                        <thead class="bg-slate-950/80">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase whitespace-nowrap">Nama</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase whitespace-nowrap">No. Telepon</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase whitespace-nowrap">Bergabung</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-slate-400 uppercase whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staffList as $staff)
                            <tr class="border-b border-slate-800 hover:bg-slate-800/40 transition">
                                <td class="px-6 py-4 text-white font-semibold whitespace-nowrap">{{ $staff->name }}</td>
                                <td class="px-6 py-4 text-slate-400 whitespace-nowrap">{{ $staff->phone }}</td>
                                <td class="px-6 py-4 text-slate-400 text-sm whitespace-nowrap">{{ $staff->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="openStaffModal({{ $staff->id }})"
                                            class="bg-blue-600 hover:bg-blue-500 text-white px-3 py-2 rounded-lg text-xs font-bold transition">Edit</button>
                                        <button wire:click="deleteStaff({{ $staff->id }})"
                                            wire:confirm="Apakah Anda yakin ingin menghapus staff ini?"
                                            class="bg-red-600 hover:bg-red-500 text-white px-3 py-2 rounded-lg text-xs font-bold transition">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-500">Belum ada data staff</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(method_exists($staffList, 'links'))
            <div class="mt-6">{{ $staffList->links() }}</div>
            @endif
        </div>
        @endif

        <!-- Tab: Customers (tidak berubah) -->
        @if($activeTab === 'customers')
        <div class="space-y-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h2 class="text-2xl font-black text-white">Data Tamu</h2>
                <input type="text" wire:model.live="customerSearch"
                    placeholder="Cari nama atau telepon..."
                    class="bg-slate-900 border border-slate-700 text-slate-200 px-4 py-2 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div class="bg-slate-900/90 border border-slate-800 rounded-3xl shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-max">
                        <thead class="bg-slate-950/80">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase whitespace-nowrap">Nama</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase whitespace-nowrap">No. Telepon</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-slate-400 uppercase whitespace-nowrap">Total Reservasi</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-slate-400 uppercase whitespace-nowrap">Selesai</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase whitespace-nowrap">Bergabung</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-slate-400 uppercase whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customerList as $customer)
                            <tr class="border-b border-slate-800 hover:bg-slate-800/40 transition">
                                <td class="px-6 py-4 text-white font-semibold whitespace-nowrap">{{ $customer->name }}</td>
                                <td class="px-6 py-4 text-slate-400 whitespace-nowrap">{{ $customer->phone }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span class="bg-indigo-600/20 text-indigo-400 px-3 py-1 rounded-lg text-sm font-bold">{{ $customer->reservations_count }}</span>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span class="bg-emerald-600/20 text-emerald-400 px-3 py-1 rounded-lg text-sm font-bold">{{ $customer->completed_reservations }}</span>
                                </td>
                                <td class="px-6 py-4 text-slate-400 text-sm whitespace-nowrap">{{ $customer->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        <button wire:click="deleteCustomer({{ $customer->id }})"
                                            wire:confirm="Apakah Anda yakin ingin menghapus tamu ini? Semua data reservasi terkait juga akan terhapus."
                                            class="bg-red-600 hover:bg-red-500 text-white px-3 py-2 rounded-lg text-xs font-bold transition">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500">Belum ada data tamu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(method_exists($customerList, 'links'))
            <div class="mt-6">{{ $customerList->links() }}</div>
            @endif
        </div>
        @endif

        <!-- Tab: Reports -->
        @if($activeTab === 'reports')
        <div class="space-y-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h2 class="text-2xl font-black text-white">Laporan Keuangan</h2>
                <div class="flex gap-2">
                    <select wire:model.live="reportPeriod"
                        class="bg-slate-900 border border-slate-700 text-slate-200 px-4 py-2 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="today">Hari Ini</option>
                        <option value="week">Minggu Ini</option>
                        <option value="month">Bulan Ini</option>
                        <option value="year">Tahun Ini</option>
                    </select>
                    <button wire:click="exportPdf" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl text-sm font-bold transition shadow-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak PDF
                    </button>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-emerald-600/20 to-emerald-900/20 border border-emerald-500/30 rounded-3xl p-6 shadow-xl">
                    <h3 class="text-sm text-emerald-300 font-bold mb-2 uppercase">Total Pendapatan</h3>
                    <div class="text-3xl font-black text-white">Rp {{ number_format($reportData['totalRevenue'] ?? 0, 0, ',', '.') }}</div>
                </div>
                <div class="bg-gradient-to-br from-blue-600/20 to-blue-900/20 border border-blue-500/30 rounded-3xl p-6 shadow-xl">
                    <h3 class="text-sm text-blue-300 font-bold mb-2 uppercase">Total Pesanan</h3>
                    <div class="text-3xl font-black text-white">{{ $reportData['totalOrders'] ?? 0 }}</div>
                </div>
                <div class="bg-gradient-to-br from-purple-600/20 to-purple-900/20 border border-purple-500/30 rounded-3xl p-6 shadow-xl">
                    <h3 class="text-sm text-purple-300 font-bold mb-2 uppercase">Rata-rata per Pesanan</h3>
                    <div class="text-3xl font-black text-white">Rp {{ number_format($reportData['avgOrderValue'] ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>

            <!-- Tren Pendapatan - Horizontal -->
            <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl p-6 border border-slate-700/50">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-3 bg-blue-500/10 rounded-xl">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white">Pendapatan Per Waktu</h3>
                </div>

                <div class="overflow-x-auto pb-2">
                    <table class="w-full min-w-max">
                        <thead>
                            <tr class="border-b border-slate-700">
                                <th class="text-left py-3 px-3 text-xs font-semibold text-slate-400 sticky left-0 bg-slate-800/90">Waktu</th>
                                @foreach($reportData['revenueChartData']['labels'] as $label)
                                    <th class="text-center py-3 px-3 text-xs font-semibold text-slate-400 whitespace-nowrap min-w-[120px]">{{ $label }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="hover:bg-slate-700/20 transition-colors">
                                <td class="py-4 px-3 text-sm font-semibold text-white sticky left-0 bg-slate-800/90">Pesanan</td>
                                @foreach($reportData['revenueChartData']['orderData'] as $amount)
                                    <td class="py-4 px-3 text-center whitespace-nowrap">
                                        <span class="font-semibold text-sm {{ $amount > 0 ? 'text-blue-400' : 'text-slate-500' }}">
                                            Rp {{ number_format($amount, 0, ',', '.') }}
                                        </span>
                                    </td>
                                @endforeach
                            </tr>
                            <tr class="hover:bg-slate-700/20 transition-colors">
                                <td class="py-4 px-3 text-sm font-semibold text-white sticky left-0 bg-slate-800/90">Reservasi</td>
                                @foreach($reportData['revenueChartData']['reservationData'] as $amount)
                                    <td class="py-4 px-3 text-center whitespace-nowrap">
                                        <span class="font-semibold text-sm {{ $amount > 0 ? 'text-purple-400' : 'text-slate-500' }}">
                                            Rp {{ number_format($amount, 0, ',', '.') }}
                                        </span>
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                        <tfoot class="border-t border-slate-600">
                            <tr>
                                <td class="py-3 px-3 text-sm font-semibold text-white sticky left-0 bg-slate-800/90">Total</td>
                                <td colspan="{{ count($reportData['revenueChartData']['labels']) }}" class="py-3 px-3 text-center">
                                    <span class="font-bold text-lg text-emerald-400">
                                        Rp {{ number_format(array_sum($reportData['revenueChartData']['orderData']) + array_sum($reportData['revenueChartData']['reservationData']), 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Data Tables - Status Pesanan & Menu Terpopuler (Sebelah-sebelahan) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Status Pesanan -->
                <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-xl">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <span class="text-2xl">📊</span> Status Pesanan
                    </h3>
                    <div class="space-y-4">
                        @php
                            $statusData = $reportData['orderStatusData'] ?? ['labels' => [], 'data' => []];
                            $totalStatus = !empty($statusData['data']) ? array_sum($statusData['data']) : 1;
                            $statusColors = [
                                'Menunggu' => ['bg' => 'bg-amber-600', 'text' => 'text-amber-400'],
                                'Diproses' => ['bg' => 'bg-blue-600', 'text' => 'text-blue-400'],
                                'Selesai' => ['bg' => 'bg-emerald-600', 'text' => 'text-emerald-400'],
                                'Dibatalkan' => ['bg' => 'bg-red-600', 'text' => 'text-red-400'],
                            ];
                        @endphp
                        @if(!empty($statusData['labels']))
                            @foreach($statusData['labels'] as $index => $label)
                                @php
                                    $count = $statusData['data'][$index] ?? 0;
                                    $percentage = $totalStatus > 0 ? ($count / $totalStatus) * 100 : 0;
                                    $color = $statusColors[$label] ?? ['bg' => 'bg-slate-600', 'text' => 'text-slate-400'];
                                @endphp
                                <div class="flex items-center justify-between p-4 bg-slate-800/50 rounded-xl">
                                    <div class="flex items-center gap-3">
                                        <div class="w-3 h-3 rounded-full {{ $color['bg'] }}"></div>
                                        <span class="text-sm font-bold text-slate-300">{{ $label }}</span>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span class="text-xs text-slate-500">{{ number_format($percentage, 1) }}%</span>
                                        <span class="text-lg font-black {{ $color['text'] }}">{{ $count }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-center text-slate-500 py-8">Belum ada data pesanan</p>
                        @endif
                    </div>
                </div>

                <!-- Menu Terpopuler -->
                <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-xl">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <span class="text-2xl">🔥</span> Menu Terpopuler
                    </h3>
                    <div class="space-y-3">
                        @php
                            $itemsData = $reportData['popularItemsData'] ?? ['labels' => [], 'data' => []];
                            $maxItems = !empty($itemsData['data']) ? max($itemsData['data']) : 1;
                            $itemColors = [
                                'bg-purple-600',
                                'bg-blue-600',
                                'bg-emerald-600',
                                'bg-amber-600',
                                'bg-red-600',
                            ];
                        @endphp
                        @if(!empty($itemsData['labels']))
                            @foreach($itemsData['labels'] as $index => $itemName)
                                @php
                                    $quantity = $itemsData['data'][$index] ?? 0;
                                    $percentage = $maxItems > 0 ? ($quantity / $maxItems) * 100 : 0;
                                    $color = $itemColors[$index % count($itemColors)];
                                @endphp
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="text-lg font-black text-slate-500">#{{ $index + 1 }}</span>
                                            <span class="text-sm font-bold text-white">{{ $itemName }}</span>
                                        </div>
                                        <span class="text-sm font-black text-indigo-400">{{ $quantity }} terjual</span>
                                    </div>
                                    <div class="w-full bg-slate-800 rounded-full h-3">
                                        <div class="{{ $color }} h-3 rounded-full transition-all duration-500" 
                                             style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-center text-slate-500 py-8">Belum ada data penjualan</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
    </main>

    <!-- Staff Modal (tidak berubah) -->
    @if($showStaffModal)
    <div class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4"
        wire:click="closeStaffModal">
        <div class="bg-slate-900 border border-slate-700 rounded-3xl shadow-2xl max-w-md w-full"
            wire:click.stop>
            <div class="px-6 py-5 border-b border-slate-800">
                <h3 class="text-xl font-bold text-white">
                    {{ $editingStaffId ? 'Edit Staff' : 'Tambah Staff Baru' }}
                </h3>
            </div>
            <form wire:submit.prevent="saveStaff" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-300 mb-2">Nama Lengkap</label>
                    <input type="text" wire:model="staffName" required
                        class="w-full bg-slate-950 border border-slate-700 text-slate-200 px-4 py-3 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                    @error('staffName') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-300 mb-2">No. Telepon</label>
                    <input type="text" wire:model="staffPhone" required
                        class="w-full bg-slate-950 border border-slate-700 text-slate-200 px-4 py-3 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                    @error('staffPhone') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-300 mb-2">
                        Password {{ $editingStaffId ? '(Kosongkan jika tidak diubah)' : '' }}
                    </label>
                    <input type="password" wire:model="staffPassword" {{ $editingStaffId ? '' : 'required' }}
                        class="w-full bg-slate-950 border border-slate-700 text-slate-200 px-4 py-3 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                    @error('staffPassword') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" wire:click="closeStaffModal"
                        class="flex-1 bg-slate-700 hover:bg-slate-600 text-white px-4 py-3 rounded-xl font-bold transition">Batal</button>
                    <button type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-3 rounded-xl font-bold transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif
    @endif
</div>