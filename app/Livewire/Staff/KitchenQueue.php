<?php

namespace App\Livewire\Staff;

use App\Models\OrderItem;
use App\Models\Order;

class KitchenQueue extends StaffDashboardBase
{

    public function mount()
    {
        parent::mount();
    }

    public function updateItemStatus($itemId, $status)
    {
        if (!$this->isLoggedIn) {
            return;
        }

        // Validate inputs
        if (!is_numeric($itemId) || $itemId <= 0) {
            return;
        }

        $validStatuses = ['menunggu', 'diproses', 'selesai', 'dibatalkan'];
        if (!in_array($status, $validStatuses, true)) {
            return;
        }

        $item = OrderItem::find($itemId);
        
        if (!$item) {
            return;
        }

        $item->update(['status_item' => $status]);

        $order = $item->order;
        
        if (!$order) {
            return;
        }

        $allCompleted = $order->orderItems()->where('status_item', '!=', 'selesai')->count() === 0;

        if ($allCompleted) {
            $order->update(['status' => 'selesai']);
        } elseif ($status === 'diproses' && $order->status === 'menunggu') {
            $order->update(['status' => 'diproses']);
        }
    }

    public function processAllCategory($category)
    {
        if (!$this->isLoggedIn) {
            return;
        }

        // Validate category
        $validCategories = ['makanan', 'minuman'];
        if (!in_array($category, $validCategories, true)) {
            return;
        }

        $items = OrderItem::where('kategori_item', $category)
            ->whereNotIn('status_item', ['selesai', 'dibatalkan'])
            ->get();

        foreach ($items as $item) {
            $item->update(['status_item' => 'diproses']);
            
            // Fresh load the order to ensure it's a Model instance
            $order = Order::find($item->order_id);
            if ($order && $order->status === 'menunggu') {
                $order->update(['status' => 'diproses']);
            }
        }
    }

    public function completeAllCategory($category)
    {
        if (!$this->isLoggedIn) {
            return;
        }

        // Validate category
        $validCategories = ['makanan', 'minuman'];
        if (!in_array($category, $validCategories, true)) {
            return;
        }

        $items = OrderItem::where('kategori_item', $category)
            ->whereNotIn('status_item', ['selesai', 'dibatalkan'])
            ->get();

        foreach ($items as $item) {
            $item->update(['status_item' => 'selesai']);
            
            // Fresh load the order to ensure it's a Model instance
            $order = Order::find($item->order_id);
            if ($order) {
                $allCompleted = $order->orderItems()->where('status_item', '!=', 'selesai')->count() === 0;
                if ($allCompleted) {
                    $order->update(['status' => 'selesai']);
                } elseif ($order->status === 'menunggu') {
                    $order->update(['status' => 'diproses']);
                }
            }
        }
    }

    public function render()
    {
        $makananItems = collect();
        $minumanItems = collect();
        $completedTodayCount = 0;
        $revenueToday = 0;

        if ($this->isLoggedIn) {
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

            $completedTodayCount = Order::where('status', 'selesai')
                ->where('status_pembayaran', 'lunas')
                ->whereDate('created_at', today())
                ->count();

            $completedOrders = Order::with('orderItems.product')
                ->where('status', 'selesai')
                ->where('status_pembayaran', 'lunas')
                ->whereDate('created_at', today())
                ->get();

            foreach ($completedOrders as $ord) {
                foreach ($ord->orderItems as $item) {
                    if ($item->product && $item->status_item !== 'dibatalkan') {
                        $revenueToday += $item->product->harga * $item->qty;
                    }
                }
            }
        }

        return view('livewire.staff.kitchen-queue', [
            'makananItems' => $makananItems,
            'minumanItems' => $minumanItems,
            'completedTodayCount' => $completedTodayCount,
            'revenueToday' => $revenueToday,
        ]);
    }
}