<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\CustomerMenu;
use App\Livewire\OrderStatus;
use App\Livewire\StaffDashboard;
use App\Models\Table;

Route::get('/', function () {
    $tables = Table::all();
    $currentHour = now()->hour;
    $isOpen = ($currentHour >= 7 && $currentHour < 17) || ($currentHour >= 19 && $currentHour < 23);
    return view('welcome', compact('tables', 'isOpen'));
})->name('customer.welcome');

// Halaman Pembeli berdasarkan Short Code Meja (prioritas utama)
Route::get('/meja/{code}', CustomerMenu::class)->name('customer.menu');

// Halaman Status Pesanan
Route::get('/pesanan/{order}', OrderStatus::class)->name('customer.order-status');

// Halaman Pembayaran Pelanggan
Route::get('/pesanan/{order}/bayar', \App\Livewire\CustomerPayment::class)->name('customer.payment');

use App\Livewire\Staff\KitchenQueue;
use App\Livewire\Staff\MenuStock;
use App\Livewire\Staff\TableQr;
use App\Livewire\Staff\OrderHistory;
use App\Models\Order;
use Illuminate\Support\Facades\Session;

// Unified Login Route (handles both customer and staff)
Route::get('/login', \App\Livewire\Auth\UnifiedLogin::class)->name('login');

// Customer Registration Route
Route::get('/register', \App\Livewire\Auth\CustomerRegister::class)->name('customer.register');

// Keep old customer login route for backward compatibility (redirects to unified)
Route::get('/customer/login', function() {
    return redirect()->route('login');
})->name('customer.login');

// Logout Route
Route::post('/logout', function() {
    auth()->logout();
    session()->flush();
    session()->regenerate();
    return redirect()->route('customer.welcome');
})->name('logout');

// Reservasi Meja (requires authentication)
Route::middleware('auth')->group(function () {
    Route::get('/reservasi/buat', \App\Livewire\ReservationCreate::class)->name('customer.reservations.create');
    Route::get('/reservasi/{reservation}/bayar', \App\Livewire\ReservationPayment::class)->name('customer.reservations.payment');
    Route::get('/reservasi', \App\Livewire\ReservationList::class)->name('customer.reservations');
    
    // Alias untuk backward compatibility
    Route::get('/reservasi/riwayat', function() {
        return redirect()->route('customer.reservations');
    })->name('customer.reservasi.create');
    
    // Profile Page (accessible by all authenticated users)
    Route::get('/profile', \App\Livewire\Profile::class)->name('profile');
});

// Old reservation form (keep for backward compatibility, but redirect to login)
Route::get('/reservasi-old', \App\Livewire\ReservationForm::class)->name('customer.reservasi');

// Staff Login (hidden URL for security + IP whitelist protection)
Route::get('/x8k3m9-staff-login', \App\Livewire\Auth\StaffLogin::class)
    ->middleware('staff.ip.whitelist')
    ->name('staff.login');

// Protected Staff Routes (all require authentication)
Route::middleware(['staff.auth'])->group(function () {
    Route::get('/staff/dashboard', \App\Livewire\StaffDashboard::class)->name('staff.dashboard');
    Route::get('/staff/antrean', \App\Livewire\Staff\KitchenQueue::class)->name('staff.dapur');
    Route::get('/staff/menu', \App\Livewire\Staff\MenuStock::class)->name('staff.menu');
    Route::get('/staff/qr', \App\Livewire\Staff\TableQr::class)->name('staff.qr');
    Route::get('/staff/riwayat', \App\Livewire\Staff\OrderHistory::class)->name('staff.riwayat');
    Route::get('/staff/reservasi', \App\Livewire\Staff\ReservationManagement::class)->name('staff.reservasi');
    Route::get('/staff/profile', \App\Livewire\Profile::class)->name('staff.profile');
    
    // Cetak Riwayat (PDF via browser print)
    Route::get('/staff/riwayat/cetak/{period}', function ($period) {
        // ✅ SECURITY FIX: Validate staff authentication
        if (!session('staff_logged_in')) {
            abort(403, 'Akses ditolak.');
        }

        // ✅ Strict validation with whitelist
        $allowedPeriods = ['today', 'week', 'month', 'year'];
        if (!in_array($period, $allowedPeriods, true)) {
            abort(404, 'Parameter tidak valid.');
        }

        // ✅ Sanitize period parameter
        $period = htmlspecialchars($period, ENT_QUOTES, 'UTF-8');

        [$start, $end] = OrderHistory::dateRangeFor($period);
        
        // ✅ Additional logging for audit trail
        \Log::info('Order history printed', [
            'staff_id' => session('staff_user_id'),
            'period' => $period,
            'ip' => request()->ip()
        ]);

        $orders = Order::with(['table', 'orderItems.product'])
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'DESC')
            ->get();

        $periodLabels = [
            'today' => 'Hari Ini',
            'week' => 'Minggu Ini',
            'month' => 'Bulan Ini',
            'year' => 'Tahun Ini',
        ];

        return view('print.order-history', compact('orders', 'period', 'periodLabels', 'start', 'end'));
    })->name('staff.riwayat.cetak');
});

// Protected Admin Routes (require admin authentication)
Route::middleware(['admin.auth'])->group(function () {
    Route::get('/admin/dashboard', \App\Livewire\AdminDashboard::class)->name('admin.dashboard');
    Route::get('/admin/profile', \App\Livewire\Profile::class)->name('admin.profile');
    
    // Export PDF Laporan
    Route::get('/admin/laporan/pdf', function () {
        $period = request('period', 'today');
        
        // Get date range
        $startDate = null;
        $endDate = null;
        switch ($period) {
            case 'today':
                $startDate = \Carbon\Carbon::today()->startOfDay();
                $endDate = \Carbon\Carbon::today()->endOfDay();
                break;
            case 'week':
                $startDate = \Carbon\Carbon::now()->startOfWeek();
                $endDate = \Carbon\Carbon::now()->endOfWeek();
                break;
            case 'month':
                $startDate = \Carbon\Carbon::now()->startOfMonth();
                $endDate = \Carbon\Carbon::now()->endOfMonth();
                break;
            case 'year':
                $startDate = \Carbon\Carbon::now()->startOfYear();
                $endDate = \Carbon\Carbon::now()->endOfYear();
                break;
        }
        
        // Get orders
        $orders = Order::where('status', 'selesai')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('orderItems.product')
            ->get();
        
        $orderRevenue = $orders->sum('total_harga');
        $reservationRevenue = \App\Models\Reservation::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');
        $totalRevenue = $orderRevenue + $reservationRevenue;
        $totalOrders = $orders->count();
        
        // Get top products
        $topProducts = \App\Models\OrderItem::with('product')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'selesai')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select('order_items.product_id', \Illuminate\Support\Facades\DB::raw('SUM(order_items.qty) as total_quantity'))
            ->groupBy('order_items.product_id')
            ->orderBy('total_quantity', 'DESC')
            ->limit(10)
            ->get();
        
        $periodLabels = [
            'today' => 'Hari Ini',
            'week' => 'Minggu Ini',
            'month' => 'Bulan Ini',
            'year' => 'Tahun Ini',
        ];
        
        return view('print.admin-report', compact(
            'orders',
            'period',
            'periodLabels',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalOrders',
            'topProducts',
            'reservationRevenue'
        ));
    })->name('admin.laporan.pdf');
});
