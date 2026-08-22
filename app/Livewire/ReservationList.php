<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class ReservationList extends Component
{
    public function cancelReservation($id)
    {
        $reservation = Reservation::withTrashed()
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if ($reservation) {
            $reservation->update(['status' => 'canceled']);
            session()->flash('message', 'Reservasi berhasil dibatalkan.');
        }
    }

    public function deleteReservation($id)
    {
        // Tetap pakai withTrashed() supaya bisa mengambil data yang sudah
        // dihapus (soft delete) oleh staff
        $reservation = Reservation::withTrashed()
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($reservation) {
            // Coba update tanpa memicu event jika pakai save() atau update()
            // Menggunakan query builder untuk memastikan customer_deleted_at terisi walau model sudah soft deleted
            Reservation::withTrashed()
                ->where('id', $id)
                ->update([
                    'customer_deleted_at' => now(),
                    'deleted_at' => now()
                ]);
                
            session()->flash('message', 'Reservasi berhasil dihapus dari daftar Anda.');
        }
    }

    public function render()
    {
        // hanya ambil reservasi yang belum dihapus oleh customer
        $reservations = Reservation::where('user_id', Auth::id())
            ->whereNull('customer_deleted_at')
            ->with(['table', 'reservationItems.product'])
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('livewire.reservation-list', [
            'reservations' => $reservations
        ])->layout('components.layouts.app');
    }
}