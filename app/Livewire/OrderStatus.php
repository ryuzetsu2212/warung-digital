<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;

class OrderStatus extends Component
{
    public $orderId;
    public $rating = 5;
    public $review = '';
    public $successMessage = '';

    public function mount($order)
    {
        // Store order ID instead of the model to avoid serialization issues
        if (is_numeric($order)) {
            $this->orderId = $order;
        } elseif (is_object($order)) {
            $this->orderId = $order->id;
        } else {
            abort(404, 'Order not found');
        }

        // Load order to check initial state
        $orderModel = $this->getOrder();
        
        if (!$orderModel) {
            abort(404, 'Order not found');
        }

        if ($orderModel->rating) {
            $this->rating = $orderModel->rating;
            $this->review = $orderModel->review;
        }

        // Redirect to payment page if order is complete but not paid
        if ($orderModel->status === 'selesai' && $orderModel->status_pembayaran === 'belum_bayar') {
            return $this->redirect(route('customer.payment', $this->orderId), navigate: false);
        }
    }

    protected function getOrder()
    {
        return Order::with('table', 'orderItems.product')->find($this->orderId);
    }

    public function submitRating()
    {
        $order = $this->getOrder();
        
        if (!$order || $order->status !== 'selesai') {
            return;
        }

        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500',
        ]);

        $order->update([
            'rating' => $this->rating,
            'review' => $this->review,
        ]);

        $this->successMessage = 'Terima kasih atas penilaian Anda!';
    }

    public function cancelOrder()
    {
        $order = $this->getOrder();
        
        if (!$order || $order->status !== 'menunggu') {
            return;
        }
        
        $order->update(['status' => 'dibatalkan']);
        foreach ($order->orderItems as $item) {
            if ($item->status_item === 'menunggu') {
                $item->update(['status_item' => 'dibatalkan']);
            }
        }
    }

    public function render()
    {
        // Always fetch fresh data from database
        $order = $this->getOrder();
        
        // Check if order still exists
        if (!$order) {
            abort(404, 'Order not found');
        }

        // Redirect to payment page if order is complete but not paid or waiting for confirmation
        if ($order->status === 'selesai' && in_array($order->status_pembayaran, ['belum_bayar', 'menunggu_konfirmasi'])) {
            $this->redirect(route('customer.payment', $this->orderId), navigate: false);
            return view('livewire.order-status', [
                'order' => $order
            ]);
        }

        return view('livewire.order-status', [
            'order' => $order
        ]);
    }
}