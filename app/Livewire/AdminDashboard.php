<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Reservation;
use App\Models\Product;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboard extends Component
{
    use WithPagination;

    public $activeTab;
    public $isLoggedIn = false;

    // Staff Management
    public $staffName = '';
    public $staffPhone = '';
    public $staffPassword = '';
    public $editingStaffId = null;
    public $showStaffModal = false;

    // Filters
    public $customerSearch = '';
    public $reportPeriod = 'today';
    
    // Manual operational control
    public $manualOverride = false;

    // Data laporan
    public $reportData = [];

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->checkAuth();
        
        // Load manual override from database
        $storedValue = Setting::getValue('admin_manual_override', null);
        // Convert stored value back to correct type
        if ($storedValue === null) {
            $this->manualOverride = false;
        } elseif ($storedValue === 'closed') {
            $this->manualOverride = 'closed';
        } elseif ($storedValue === '1' || $storedValue === 'true' || $storedValue === true) {
            $this->manualOverride = true;
        } else {
            $this->manualOverride = false;
        }
        
        // Get active tab from URL query parameter, default to 'statistik'
        $this->activeTab = request()->query('tab', 'statistik');
        
        $this->loadReportData();
    }

    public function checkAuth()
    {
        $this->isLoggedIn = Session::get('staff_logged_in') === true 
                         && Session::get('staff_role') === 'admin';
        
        if (!$this->isLoggedIn) {
            return redirect()->route('staff.login');
        }
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
        if ($tab === 'reports') {
            $this->loadReportData();
        }
        
        // Update URL when tab changes without page reload
        $this->js("window.history.replaceState(null, '', '?tab={$tab}')");
    }

    public function updatedReportPeriod()
    {
        $this->loadReportData();
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('staff.login');
    }

    // Staff Management Methods
    public function openStaffModal($id = null)
    {
        $this->resetStaffForm();
        if ($id) {
            $staff = User::find($id);
            if ($staff) {
                $this->editingStaffId = $staff->id;
                $this->staffName = $staff->name;
                $this->staffPhone = $staff->phone;
            }
        }
        $this->showStaffModal = true;
    }

    public function closeStaffModal()
    {
        $this->showStaffModal = false;
        $this->resetStaffForm();
    }

    public function resetStaffForm()
    {
        $this->editingStaffId = null;
        $this->staffName = '';
        $this->staffPhone = '';
        $this->staffPassword = '';
    }

    public function saveStaff()
    {
        $this->validate([
            'staffName' => 'required|min:3',
            'staffPhone' => 'required|numeric|digits_between:10,15',
            'staffPassword' => $this->editingStaffId ? 'nullable|min:6' : 'required|min:6',
        ]);

        if ($this->editingStaffId) {
            $staff = User::find($this->editingStaffId);
            $staff->name = $this->staffName;
            $staff->phone = $this->staffPhone;
            if ($this->staffPassword) {
                $staff->password = Hash::make($this->staffPassword);
            }
            $staff->save();
            session()->flash('message', 'Data staff berhasil diupdate.');
        } else {
            $user = new User();
            $user->name = $this->staffName;
            $user->phone = $this->staffPhone;
            $user->password = Hash::make($this->staffPassword);
            $user->role = 'staff';
            $user->save();
            session()->flash('message', 'Staff baru berhasil ditambahkan.');
        }

        $this->closeStaffModal();
    }

    public function deleteStaff($id)
    {
        $staff = User::find($id);
        if ($staff && $staff->role === 'staff') {
            $staff->delete();
            session()->flash('message', 'Staff berhasil dihapus.');
        }
    }

    public function deleteCustomer($id)
    {
        $customer = User::find($id);
        if ($customer && $customer->role === 'customer') {
            // Hapus semua reservasi terkait customer
            $customer->reservations()->delete();
            
            // Hapus customer
            $customer->delete();
            session()->flash('message', 'Tamu dan semua data reservasi terkait berhasil dihapus.');
        }
    }

    public function refresh()
    {
        $this->loadReportData();
        // Render otomatis dipanggil Livewire setelah method
    }

    public function render()
    {
        if (!$this->isLoggedIn) {
            return view('livewire.admin-dashboard');
        }

        // Statistics Data
        $totalStaff = User::where('role', 'staff')->count();
        $totalCustomers = User::where('role', 'customer')->count();
        
        $todayCompletedOrders = Order::with('orderItems.product')
            ->whereDate('created_at', Carbon::today())
            ->where('status', 'selesai')
            ->get();
        
        $todayRevenue = $this->getCombinedRevenue(Carbon::today()->startOfDay(), Carbon::today()->endOfDay());
        
        $todayOrders = Order::whereDate('created_at', Carbon::today())->count();
        $activeReservations = Reservation::where('status', 'confirmed')->count();
        
        // Meja Tersedia
        $totalTables = \App\Models\Table::count();
        $occupiedTables = \App\Models\Table::where('status_meja', 'terisi')->count();
        $availableTables = $totalTables - $occupiedTables;
        
        // Waktu Operasional - Jam operasional warung: 07:00-17:00 dan 19:00-23:00
        $currentHour = Carbon::now()->hour;
        $autoIsOpen = ($currentHour >= 7 && $currentHour < 17) || ($currentHour >= 19 && $currentHour < 23);
        
        // Manual override logic
        // manualOverride null/false = follow auto schedule
        // manualOverride true = force open
        // manualOverride 'closed' = force closed
        if ($this->manualOverride === 'closed') {
            $isOpen = false;
            $operationalStatus = 'Tutup';
            $operationalTime = 'Ditutup manual oleh admin';
        } elseif ($this->manualOverride === true) {
            $isOpen = true;
            $operationalStatus = 'Buka';
            $operationalTime = 'Dibuka manual oleh admin';
        } else {
            // Follow automatic schedule
            $isOpen = $autoIsOpen;
            $operationalStatus = $isOpen ? 'Buka' : 'Tutup';
            
            if ($isOpen) {
                if ($currentHour >= 7 && $currentHour < 17) {
                    $operationalTime = 'Tutup pukul 17:00';
                } else {
                    $operationalTime = 'Tutup pukul 23:00';
                }
            } else {
                if ($currentHour < 7) {
                    $operationalTime = 'Buka pukul 07:00';
                } elseif ($currentHour >= 17 && $currentHour < 19) {
                    $operationalTime = 'Buka pukul 19:00';
                } else {
                    $operationalTime = 'Buka besok pukul 07:00';
                }
            }
        }
        
        // ponytail: low-stock query dihapus - kolom 'stok' tidak ada & view tidak memakainya.
        // Tambahkan kembali hanya jika kolom stok dibuat di tabel products.

        $staffList = User::where('role', 'staff')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        $customerList = User::where('role', 'customer')
            ->when($this->customerSearch, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->customerSearch . '%')
                      ->orWhere('phone', 'like', '%' . $this->customerSearch . '%');
                });
            })
            ->withCount(['reservations', 'reservations as completed_reservations' => function($query) {
                $query->where('status', 'completed');
            }])
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        // Pastikan reportData selalu terisi
        if (empty($this->reportData)) {
            $this->loadReportData();
        }

        return view('livewire.admin-dashboard', [
            'totalStaff' => $totalStaff,
            'totalCustomers' => $totalCustomers,
            'todayRevenue' => $todayRevenue,
            'todayOrders' => $todayOrders,
            'activeReservations' => $activeReservations,
            'staffList' => $staffList,
            'customerList' => $customerList,
            'reportData' => $this->reportData,
            'totalTables' => $totalTables,
            'availableTables' => $availableTables,
            'occupiedTables' => $occupiedTables,
            'isOpen' => $isOpen,
            'operationalStatus' => $operationalStatus,
            'operationalTime' => $operationalTime,
        ])->layout('components.layouts.app');
    }

    private function loadReportData()
    {
        $this->reportData = $this->getReportData();
    }

    private function getReportData()
    {
        // Tentukan rentang tanggal berdasarkan periode
        $startDate = null;
        $endDate = null;
        switch ($this->reportPeriod) {
            case 'today':
                $startDate = Carbon::today()->startOfDay();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
        }

        $query = Order::where('status', 'selesai');
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        $totalOrders = $query->count();

        $totalRevenue = $this->getCombinedRevenue($startDate, $endDate);

        return [
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'avgOrderValue' => $totalOrders > 0 ? $totalRevenue / $totalOrders : 0,
            'revenueChartData' => $this->getRevenueChartData($startDate, $endDate),
            'popularItemsData' => $this->getPopularItemsData($startDate, $endDate),
            'orderStatusData' => $this->getOrderStatusData($startDate, $endDate),
        ];
    }

    private function getRevenueChartData($startDate, $endDate)
    {
        if (!$startDate || !$endDate) {
            $startDate = Carbon::today()->startOfDay();
            $endDate = Carbon::today()->endOfDay();
        }

        $labels = [];
        $orderData = [];
        $reservationData = [];
        $period = $this->reportPeriod;
        
        // Debug: Log untuk melihat apakah fungsi dipanggil
        \Log::info('getRevenueChartData called', [
            'period' => $period,
            'startDate' => $startDate->toDateTimeString(),
            'endDate' => $endDate->toDateTimeString()
        ]);

        if ($period === 'today') {
            // total_harga = accessor (sum order_items); muat 1 query join
            $todayOrders = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('orders.status', 'selesai')
                ->whereNot('order_items.status_item', 'dibatalkan')
                ->whereDate('orders.created_at', $startDate->format('Y-m-d'))
                ->select('orders.id', DB::raw('products.harga * order_items.qty as line_total'), 'orders.created_at')
                ->get();

            $todayReservations = Reservation::where('status', 'completed')
                ->whereDate('created_at', $startDate->format('Y-m-d'))
                ->get();

            // Group by hour menggunakan Carbon
            $hourlyOrderRevenue = [];
            foreach ($todayOrders as $line) {
                $hour = (int) $line->created_at->format('G');
                if (!isset($hourlyOrderRevenue[$hour])) {
                    $hourlyOrderRevenue[$hour] = 0;
                }
                $hourlyOrderRevenue[$hour] += $line->line_total;
            }

            $hourlyReservationRevenue = [];
            foreach ($todayReservations as $reservation) {
                $hour = (int) $reservation->created_at->format('G');
                if (!isset($hourlyReservationRevenue[$hour])) {
                    $hourlyReservationRevenue[$hour] = 0;
                }
                $hourlyReservationRevenue[$hour] += $reservation->total_amount;
            }

            // Buat array hanya untuk jam 7 pagi sampai jam 6 sore (07:00-18:00)
            for ($hour = 7; $hour <= 18; $hour++) {
                $labels[] = sprintf('%02d:00', $hour);
                $orderData[] = $hourlyOrderRevenue[$hour] ?? 0;
                $reservationData[] = $hourlyReservationRevenue[$hour] ?? 0;
            }
        } elseif ($period === 'week' || $period === 'month') {
            // 1 grouping query per sumber. total_harga bukan kolom, jadi dihitung
            // lewat join order_items+products lalu dijumlah per tanggal order.
            $orderRows = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('orders.status', 'selesai')
                ->whereNot('order_items.status_item', 'dibatalkan')
                ->whereBetween('orders.created_at', [$startDate, $endDate])
                ->select(DB::raw('DATE(orders.created_at) as d, SUM(products.harga * order_items.qty) as rev'))
                ->groupByRaw('DATE(orders.created_at)')
                ->get()
                ->keyBy('d');

            $resRows = Reservation::where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select(DB::raw('DATE(created_at) as d, SUM(total_amount) as rev'))
                ->groupByRaw('DATE(created_at)')
                ->get()
                ->keyBy('d');

            $days = ($period === 'week') ? 7 : $startDate->daysInMonth;
            for ($i = 0; $i < $days; $i++) {
                $date = $startDate->copy()->addDays($i);
                $labels[] = ($period === 'week') ? $date->format('D') : $date->day;
                $d = $date->format('Y-m-d');
                $orderData[] = (float) ($orderRows[$d]->rev ?? 0);
                $reservationData[] = (float) ($resRows[$d]->rev ?? 0);
            }
        } elseif ($period === 'year') {
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            $orderRows = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('orders.status', 'selesai')
                ->whereNot('order_items.status_item', 'dibatalkan')
                ->whereBetween('orders.created_at', [$startDate, $endDate])
                ->select(DB::raw('TO_CHAR(orders.created_at, \'YYYY-MM\') as m, SUM(products.harga * order_items.qty) as rev'))
                ->groupByRaw('TO_CHAR(orders.created_at, \'YYYY-MM\')')
                ->get()
                ->keyBy('m');
            $resRows = Reservation::where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select(DB::raw('TO_CHAR(created_at, \'YYYY-MM\') as m, SUM(total_amount) as rev'))
                ->groupByRaw('TO_CHAR(created_at, \'YYYY-MM\')')
                ->get()
                ->keyBy('m');
            for ($month = 1; $month <= 12; $month++) {
                $labels[] = $months[$month - 1];
                $m = Carbon::create($startDate->year, $month, 1)->format('Y-m');
                $orderData[] = (float) ($orderRows[$m]->rev ?? 0);
                $reservationData[] = (float) ($resRows[$m]->rev ?? 0);
            }
        }

        return [
            'labels' => $labels,
            'orderData' => $orderData,
            'reservationData' => $reservationData,
        ];
    }

    private function getPopularItemsData($startDate, $endDate)
    {
        if (!$startDate || !$endDate) {
            $startDate = Carbon::today()->startOfDay();
            $endDate = Carbon::today()->endOfDay();
        }

        $topItems = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.status', 'selesai')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select('order_items.product_id', 'products.nama', DB::raw('SUM(order_items.qty) as total_quantity'))
            ->groupBy('order_items.product_id', 'products.nama')
            ->orderBy('total_quantity', 'DESC')
            ->limit(5)
            ->get();

        $labels = $topItems->pluck('nama')->all();
        $data = $topItems->pluck('total_quantity')->all();

        return ['labels' => $labels, 'data' => $data];
    }

    private function getOrderStatusData($startDate, $endDate)
    {
        if (!$startDate || !$endDate) {
            $startDate = Carbon::today()->startOfDay();
            $endDate = Carbon::today()->endOfDay();
        }

        $statuses = Order::whereBetween('created_at', [$startDate, $endDate])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        $statusLabels = [
            'menunggu' => 'Menunggu',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
        ];

        $labels = [];
        $data = [];
        foreach ($statuses as $status) {
            $labels[] = $statusLabels[$status->status] ?? ucfirst($status->status);
            $data[] = $status->count;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function getCombinedRevenue($startDate, $endDate)
    {
        // total_harga adalah accessor (sum order_items), bukan kolom orders.
        // Hitung dalam satu join + group by per order.
        $orderRevenue = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.status', 'selesai')
            ->whereNot('order_items.status_item', 'dibatalkan')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->sum(DB::raw('products.harga * order_items.qty'));

        $reservationRevenue = Reservation::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');

        return $orderRevenue + $reservationRevenue;
    }
    public function exportPdf()
    {
        // Open PDF in new tab/window
        $url = route('admin.laporan.pdf', ['period' => $this->reportPeriod]);
        $this->js("window.open('$url', '_blank')");
    }
    
    public function toggleOperationalStatus()
    {
        $currentHour = Carbon::now()->hour;
        $autoIsOpen = ($currentHour >= 7 && $currentHour < 17) || ($currentHour >= 19 && $currentHour < 23);
        
        if ($this->manualOverride === 'closed') {
            // Sedang ditutup manual, buka manual
            $this->manualOverride = true;
            // Aktifkan semua QR meja dan ubah status jadi tersedia
            \App\Models\Table::query()->update([
                'qr_available' => true,
                'status_meja' => 'tersedia'
            ]);
            session()->flash('message', 'Warung dibuka secara manual. Semua QR meja diaktifkan.');
        } elseif ($this->manualOverride === true) {
            // Sedang dibuka manual, kembalikan ke jadwal otomatis
            $this->manualOverride = false;
            session()->flash('message', 'Warung mengikuti jadwal otomatis.');
        } else {
            // Sedang otomatis
            if ($autoIsOpen) {
                // Jam operasional, tutup paksa
                $this->manualOverride = 'closed';
                // Nonaktifkan semua QR meja dan ubah status jadi tertutup
                \App\Models\Table::query()->update([
                    'qr_available' => false,
                    'status_meja' => 'tertutup'
                ]);
                session()->flash('message', 'Warung ditutup secara manual. Semua QR meja dinonaktifkan.');
            } else {
                // Di luar jam operasional, buka paksa
                $this->manualOverride = true;
                // Aktifkan semua QR meja dan ubah status jadi tersedia
                \App\Models\Table::query()->update([
                    'qr_available' => true,
                    'status_meja' => 'tersedia'
                ]);
                session()->flash('message', 'Warung dibuka secara manual. Semua QR meja diaktifkan.');
            }
        }
        
        // Store manual override in database for persistence across requests and instances
        Setting::setValue('admin_manual_override', $this->manualOverride);
    }
}
