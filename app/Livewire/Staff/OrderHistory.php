<?php

namespace App\Livewire\Staff;

use App\Models\Order;
use Illuminate\Support\Carbon;

class OrderHistory extends StaffDashboardBase
{

    public $period = 'today';
    public $filterDate = '';
    public $filterTable = '';
    public $filterRating = '';
    public $sortByTotal = '';
    public $successMessage = '';

    public function mount()
    {
        parent::mount();
        $this->period = 'today';
    }

    public function setPeriod($period)
    {
        if (in_array($period, ['today', 'week', 'month', 'year'])) {
            $this->period = $period;
            $this->filterDate = ''; // Reset date filter when changing general period tab
        }
    }

    public static function dateRangeFor($period)
    {
        return match ($period) {
            'week' => [now()->startOfWeek(), now()->endOfWeek()],
            'month' => [now()->startOfMonth(), now()->endOfMonth()],
            'year' => [now()->startOfYear(), now()->endOfYear()],
            default => [today()->startOfDay(), today()->endOfDay()],
        };
    }

    public function confirmPayment($orderId)
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->status_pembayaran = 'lunas';
            $order->status = 'selesai';
            $order->save();

            // Reset table status to available after payment is confirmed
            if ($order->table) {
                $order->table->update([
                    'status_meja' => 'tersedia',
                    'active_session_token' => null,
                ]);
            }

            $order->load('table', 'orderItems.product');

            $this->successMessage = "Pembayaran pesanan #{$orderId} berhasil dikonfirmasi LUNAS.";
        }
    }

    public function cancelPayment($orderId)
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->status_pembayaran = 'belum_bayar';
            $order->status = 'menunggu';
            $order->save();

            // Restore table status to occupied when payment is cancelled
            if ($order->table) {
                $order->table->update([
                    'status_meja' => 'terisi',
                    'active_session_token' => $order->session_token,
                ]);
            }

            $order->load('table', 'orderItems.product');

            $this->successMessage = "Pembayaran pesanan #{$orderId} berhasil dibatalkan.";
        }
    }

    public function render()
    {
        $orders = collect();
        $orderCount = 0;
        $revenue = 0;

        if ($this->isLoggedIn) {
            $query = Order::with(['table', 'orderItems.product']);

            if (!empty($this->filterDate)) {
                $query->whereDate('created_at', $this->filterDate);
            } else {
                [$start, $end] = self::dateRangeFor($this->period);
                $query->whereBetween('created_at', [$start, $end]);
            }

            if (!empty($this->filterTable)) {
                $query->where('table_id', $this->filterTable);
            }

            if ($this->filterRating !== '') {
                if ($this->filterRating === 'null') {
                    $query->whereNull('rating');
                } else {
                    $query->where('rating', $this->filterRating);
                }
            }

            // Fetch all matching orders first to calculate totals & sort if needed
            $allOrders = $query->get();

            // Calculate total price accessor/helper for sorting
            $allOrders->each(function($ord) {
                $total = 0;
                foreach ($ord->orderItems as $item) {
                    if ($item->product && $item->status_item !== 'dibatalkan') {
                        $total += $item->product->harga * $item->qty;
                    }
                }
                $ord->calculated_total = $total;
            });

            if ($this->sortByTotal === 'highest') {
                $allOrders = $allOrders->sortByDesc('calculated_total');
            } elseif ($this->sortByTotal === 'lowest') {
                $allOrders = $allOrders->sortBy('calculated_total');
            } else {
                $allOrders = $allOrders->sortByDesc('created_at');
            }

            $orders = $allOrders;

            $completedOrders = $orders->where('status', 'selesai');
            $orderCount = $completedOrders->count();

            foreach ($completedOrders as $ord) {
                $revenue += $ord->calculated_total;
            }
        }

        $periodLabels = [
            'today' => 'Hari Ini',
            'week' => 'Minggu Ini',
            'month' => 'Bulan Ini',
            'year' => 'Tahun Ini',
        ];

        return view('livewire.staff.order-history', [
            'recentOrders' => $orders,
            'completedCount' => $orderCount,
            'revenue' => $revenue,
            'periodLabels' => $periodLabels,
        ]);
    }
}