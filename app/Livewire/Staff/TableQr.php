<?php

namespace App\Livewire\Staff;

use App\Models\Table;
use App\Models\Order;
use Illuminate\Support\Str;

class TableQr extends StaffDashboardBase
{

    public function mount()
    {
        parent::mount();
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
            'qr_available' => true
        ]);

        $this->successMessage = "Sesi Meja " . $table->nomor_meja . " berhasil direset/dikosongkan dan QR Code diaktifkan kembali.";
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
            'qr_available' => false
        ]);

        $this->successMessage = "Meja " . $table->nomor_meja . " berhasil ditandai TERISI (dikunci dari pemesanan iseng) dan QR Code dinonaktifkan.";
    }

    public function clearMessage()
    {
        $this->successMessage = '';
    }

    public function render()
    {
        $tables = collect();
        $completedTodayCount = 0;
        $revenueToday = 0;

        if ($this->isLoggedIn) {
            $tables = Table::all();

            $completedTodayCount = Order::where('status', 'selesai')
                ->whereDate('created_at', today())
                ->count();

            $completedOrders = Order::with('orderItems.product')
                ->where('status', 'selesai')
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

        return view('livewire.staff.table-qr', [
            'tables' => $tables,
            'completedTodayCount' => $completedTodayCount,
            'revenueToday' => $revenueToday,
        ]);
    }
}