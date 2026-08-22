<?php

namespace App\Livewire;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Table;
use App\Livewire\Concerns\HandlesTableAutoStatus;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Livewire\Staff\StaffDashboardBase;

class StaffDashboard extends StaffDashboardBase
{
    use HandlesTableAutoStatus;

    public $tab; // Current active tab
    public $staffName = ''; // Staff display name

    // Filter variables
    public $revenueFilter = 'hari ini'; // hari ini, minggu ini, bulan ini, tahun ini
    public $tableFilter = ''; // Filter by table number
    public $dateFilter = ''; // Filter by date (YYYY-MM-DD)
    public $ratingFilter = ''; // Filter by rating (0-5)

    public function mount()
    {
        parent::mount();
        
        // Get active tab from URL query parameter, default to 'antrean'
        $this->tab = request()->query('tab', 'antrean');
        
        // Get staff name from session
        $this->staffName = Session::get('staff_username', 'Staff');
    }

    public function updatedTab($value)
    {
        // Update URL when tab changes without page reload
        $this->js("window.history.replaceState(null, '', '?tab={$value}')");
    }

    public function toggleProductAvailability($productId)
    {
        if (!$this->isLoggedIn) {
            return;
        }

        $product = \App\Models\Product::findOrFail($productId);
        $product->is_available = !$product->is_available;
        $product->save();

        $statusText = $product->is_available ? 'TERSEDIA (Ditampilkan ke Pelanggan)' : 'HABIS / KOSONG (Disembunyikan dari Pelanggan)';
        $this->successMessage = "Status menu \"{$product->nama}\" berhasil diubah menjadi: {$statusText}";
    }

    public function clearMessage()
    {
        $this->successMessage = '';
    }

    public function resetTableSession($tableId)
    {
        if (!$this->isLoggedIn) {
            return;
        }

        $table = Table::findOrFail($tableId);
        $table->update([
            'status_meja' => 'tersedia',
            'active_session_token' => null,
        ]);

        $this->successMessage = "Sesi Meja " . $table->nomor_meja . " berhasil direset/dikosongkan.";
    }

    public function setTableOccupied($tableId)
    {
        if (!$this->isLoggedIn) {
            return;
        }

        $table = Table::findOrFail($tableId);
        $table->update([
            'status_meja' => 'terisi',
            'active_session_token' => (string) Str::uuid(),
        ]);

        $this->successMessage = "Meja " . $table->nomor_meja . " berhasil ditandai TERISI (dikunci dari pemesanan iseng).";
    }

    public function updateItemStatus($itemId, $status)
    {
        if (!$this->isLoggedIn) {
            return;
        }

        $item = OrderItem::findOrFail($itemId);
        $item->update(['status_item' => $status]);

        $order = $item->order;
        $allCompleted = $order->orderItems()->where('status_item', '!=', 'selesai')->count() === 0;

        if ($allCompleted) {
            $order->update(['status' => 'selesai']);
        } elseif ($status === 'diproses' && $order->status === 'menunggu') {
            $order->update(['status' => 'diproses']);
        }
    }

    public function render()
    {
        $makananItems = collect();
        $minumanItems = collect();
        $tables = collect();
        $recentOrders = collect();
        $completedTodayCount = 0;
        $revenueToday = 0;
        $allProducts = collect();

        if ($this->isLoggedIn) {
            $allProducts = \App\Models\Product::all();
            $makananItems = OrderItem::with(['order.table', 'product'])
                ->where('kategori_item', 'makanan')
                ->whereNotIn('status_item', ['selesai', 'dibatalkan'])
                ->orderBy('created_at', 'ASC')
                ->get();

            $minumanItems = OrderItem::with(['order.table', 'product'])
                ->where('kategori_item', 'minuman')
                ->whereNotIn('status_item', ['selesai', 'dibatalkan'])
                ->orderBy('created_at', 'ASC')
                ->get();

            $tables = Table::all();

            // Build query for recent orders with filters
            $recentOrdersQuery = Order::with(['table', 'orderItems.product'])
                ->where('status', '!=', 'dibatalkan')
                ->orderBy('created_at', 'DESC');

            // Apply revenue period filter
            if ($this->revenueFilter === 'hari ini') {
                $recentOrdersQuery->whereDate('created_at', today());
            } elseif ($this->revenueFilter === 'minggu ini') {
                $recentOrdersQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($this->revenueFilter === 'bulan ini') {
                $recentOrdersQuery->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            } elseif ($this->revenueFilter === 'tahun ini') {
                $recentOrdersQuery->whereYear('created_at', now()->year);
            }

            // Apply table filter
            if (!empty($this->tableFilter)) {
                $recentOrdersQuery->whereHas('table', function($query) {
                    $query->where('nomor_meja', $this->tableFilter);
                });
            }

            // Apply date filter
            if (!empty($this->dateFilter)) {
                $recentOrdersQuery->whereDate('created_at', $this->dateFilter);
            }

            // Apply rating filter
            if (!empty($this->ratingFilter)) {
                $recentOrdersQuery->where('rating', $this->ratingFilter);
            }

            $recentOrders = $recentOrdersQuery->limit(20)->get();

            // Build query for completed count with filters
            $completedTodayQuery = Order::where('status', 'selesai');

            // Apply revenue period filter
            if ($this->revenueFilter === 'hari ini') {
                $completedTodayQuery->whereDate('created_at', today());
            } elseif ($this->revenueFilter === 'minggu ini') {
                $completedTodayQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($this->revenueFilter === 'bulan ini') {
                $completedTodayQuery->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            } elseif ($this->revenueFilter === 'tahun ini') {
                $completedTodayQuery->whereYear('created_at', now()->year);
            }

            // Apply table filter
            if (!empty($this->tableFilter)) {
                $completedTodayQuery->whereHas('table', function($query) {
                    $query->where('nomor_meja', $this->tableFilter);
                });
            }

            // Apply date filter
            if (!empty($this->dateFilter)) {
                $completedTodayQuery->whereDate('created_at', $this->dateFilter);
            }

            // Apply rating filter
            if (!empty($this->ratingFilter)) {
                $completedTodayQuery->where('rating', $this->ratingFilter);
            }

            $completedTodayCount = $completedTodayQuery->count();

            // Build query for completed orders with filters for revenue calculation
            $completedOrdersQuery = Order::with('orderItems.product')
                ->where('status', 'selesai')
                ->where('status_pembayaran', 'lunas');

            // Apply revenue period filter
            if ($this->revenueFilter === 'hari ini') {
                $completedOrdersQuery->whereDate('created_at', today());
            } elseif ($this->revenueFilter === 'minggu ini') {
                $completedOrdersQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($this->revenueFilter === 'bulan ini') {
                $completedOrdersQuery->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            } elseif ($this->revenueFilter === 'tahun ini') {
                $completedOrdersQuery->whereYear('created_at', now()->year);
            }

            // Apply table filter
            if (!empty($this->tableFilter)) {
                $completedOrdersQuery->whereHas('table', function($query) {
                    $query->where('nomor_meja', $this->tableFilter);
                });
            }

            // Apply date filter
            if (!empty($this->dateFilter)) {
                $completedOrdersQuery->whereDate('created_at', $this->dateFilter);
            }

            // Apply rating filter
            if (!empty($this->ratingFilter)) {
                $completedOrdersQuery->where('rating', $this->ratingFilter);
            }

            $completedOrders = $completedOrdersQuery->get();

            // Revenue is calculated ONLY from completed & paid orders,
            // and ONLY for items that are NOT cancelled.
            // Cancelled orders/items are never included in revenue.
            $revenueToday = 0;
            foreach ($completedOrders as $ord) {
                foreach ($ord->orderItems as $item) {
                    if ($item->product && $item->status_item !== 'dibatalkan') {
                        $revenueToday += $item->product->harga * $item->qty;
                    }
                }
            }
        }

        return view('livewire.staff-dashboard', [
            'makananItems' => $makananItems,
            'minumanItems' => $minumanItems,
            'tables' => $tables,
            'recentOrders' => $recentOrders,
            'completedTodayCount' => $completedTodayCount,
            'revenueToday' => $revenueToday,
            'allProducts' => $allProducts,
        ]);
    }
}
