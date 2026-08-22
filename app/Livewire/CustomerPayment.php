<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;

class CustomerPayment extends Component
{
    public $order;
    public $metode_pembayaran = 'cash';
    public $successMessage = '';
    public $rating = 5;
    public $review = '';

    public function mount(Order $order)
    {
        $this->order = $order->load('table', 'orderItems.product');
        $this->metode_pembayaran = 'cash';

        if (!$this->order) {
            abort(404, 'Order not found');
        }

        if ($this->order->rating) {
            $this->rating = $this->order->rating;
            $this->review = $this->order->review;
        }

    }

    public function submitPayment()
    {
        if ($this->metode_pembayaran === 'qris') {
            $this->successMessage = 'Pembayaran pesanan meja harus melalui kasir.';
            return;
        }

        $this->order->update([
            'metode_pembayaran' => 'cash',
            'status_pembayaran' => 'menunggu_konfirmasi',
        ]);

        $this->order->refresh();
        $this->successMessage = 'Permintaan pembayaran telah dikirim!';

        $this->dispatch('payment-requested', [
            'order_id' => $this->order->id,
            'table_no' => $this->order->table->nomor_meja ?? '-',
        ]);
    }

    public function submitRating()
    {
        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500',
        ]);

        $this->order->update([
            'rating' => $this->rating,
            'review' => $this->review,
        ]);

        $this->successMessage = 'Terima kasih atas penilaian Anda!';
    }

    public function downloadReceipt()
    {
        $this->dispatch('download-receipt', ['orderId' => $this->order->id]);
    }

    public function render()
    {
        if ($this->order) {
            $this->order->refresh();
            $this->order->load('table', 'orderItems.product');
        }

        return view('livewire.customer-payment');
    }
}