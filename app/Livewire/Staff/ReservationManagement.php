<?php

namespace App\Livewire\Staff;

use App\Models\Reservation;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ReservationManagement extends StaffDashboardBase
{
    public $editReservationId;
    public $statusFilter = 'all';
    public $selectedReservation = null;
    public $showDetailModal = false;
    
    // Bulk delete properties
    public $selectedIds = [];
    public $selectAll = false;
    
    // Date filter property
    public $filterDate;
    
    // Revenue filter
    public $revenueFilter = null; // today, week, month, year
    
    protected $listeners = ['reservation-updated' => '$refresh'];

    public function showDetail($reservationId)
    {
        $this->selectedReservation = Reservation::with(['table', 'user', 'reservationItems.product'])
            ->find($reservationId);
        $this->showDetailModal = true;
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedReservation = null;
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            // Select all visible reservations on current page (hanya yang bisa dihapus)
            $query = Reservation::query();
            
            // Hanya pilih reservasi dengan status pending atau canceled
            $query->whereIn('status', ['pending', 'canceled']);
            
            if ($this->statusFilter && $this->statusFilter !== 'all') {
                $query->where('status', $this->statusFilter);
            }
            
            if ($this->filterDate) {
                $query->whereDate('reservation_date', $this->filterDate);
            }
            
            $this->selectedIds = $query->pluck('id')->toArray();
        } else {
            $this->selectedIds = [];
        }
    }

    public function bulkDelete()
    {
        if (empty($this->selectedIds)) {
            session()->flash('error', 'Tidak ada data yang dipilih.');
            return;
        }

        try {
            // Validasi: hanya hapus reservasi dengan status pending atau canceled
            $reservationsToDelete = Reservation::whereIn('id', $this->selectedIds)
                ->whereIn('status', ['pending', 'canceled'])
                ->get();
            
            if ($reservationsToDelete->isEmpty()) {
                session()->flash('error', 'Tidak ada reservasi yang dapat dihapus. Hanya reservasi dengan status "Menunggu" atau "Dibatalkan" yang dapat dihapus.');
                return;
            }
            
            // Soft delete
            $count = $reservationsToDelete->count();
            foreach ($reservationsToDelete as $reservation) {
                $reservation->delete();
            }
            
            session()->flash('success', "Berhasil menghapus {$count} reservasi dari tampilan staff.");
            
            // Reset selection
            $this->selectedIds = [];
            $this->selectAll = false;
            
            $this->dispatch('reservation-updated');
        } catch (\Exception $e) {
            Log::error('Bulk delete reservations error: ' . $e->getMessage());
            session()->flash('error', 'Gagal menghapus reservasi: ' . $e->getMessage());
        }
    }

    public function resetFilters()
    {
        $this->statusFilter = 'all';
        $this->filterDate = null;
        $this->revenueFilter = null;
        $this->selectedIds = [];
        $this->selectAll = false;
    }

    public function setRevenueFilter($period)
    {
        $this->revenueFilter = $period;
    }

    private function calculateRevenueByPeriod($startDate, $endDate)
    {
        $reservations = Reservation::with('reservationItems.product')
            ->where('status', 'completed')
            ->whereIn('payment_status', ['paid', 'dp_paid'])
            ->whereBetween('payment_time', [$startDate, $endDate])
            ->get();
        
        $total = 0;
        $count = 0;
        foreach ($reservations as $reservation) {
            if ($reservation->total_amount > 0) {
                $total += $reservation->total_amount;
            } else {
                foreach ($reservation->reservationItems as $item) {
                    if ($item->product) {
                        $total += $item->product->harga * $item->quantity;
                    }
                }
            }
            $count++;
        }
        
        return ['total' => $total, 'count' => $count];
    }

public function confirmDpPayment($reservationId)
{
    $reservation = Reservation::find($reservationId);
    if (!$reservation) {
        session()->flash('error', 'Reservasi tidak ditemukan.');
        return;
    }

    $reservation->payment_status = 'dp_paid';
    $reservation->status = 'confirmed';
    $reservation->payment_time = now();
    $reservation->save();

    session()->flash('success', 'Pembayaran DP 50% berhasil dikonfirmasi dan reservasi telah diaktifkan.');
    $this->dispatch('reservation-updated');

    if ($this->showDetailModal && $this->selectedReservation && $this->selectedReservation->id == $reservationId) {
        $this->selectedReservation = $reservation;
    }
}

public function updatePaymentStatus($reservationId, $paymentStatus)
{
    $reservation = Reservation::find($reservationId);
    if (!$reservation) {
        session()->flash('error', 'Reservasi tidak ditemukan.');
        return;
    }

    $reservation->payment_status = $paymentStatus;
    if ($paymentStatus !== 'pending' && !$reservation->payment_time) {
        $reservation->payment_time = now();
    }

    // Auto-confirm reservation when payment is completed or dp is paid
    if (in_array($paymentStatus, ['paid', 'dp_paid']) && $reservation->status === 'pending') {
        $reservation->status = 'confirmed';
    }

    $reservation->save();

    session()->flash('success', 'Status pembayaran berhasil diperbarui.');
    $this->dispatch('reservation-updated');

    if ($this->showDetailModal && $this->selectedReservation && $this->selectedReservation->id == $reservationId) {
        $this->selectedReservation = $reservation;
    }
}
    public function updateStatus($reservationId, $status)
    {
        // ✅ SECURITY FIX: Authorize staff access
        if (!session('staff_logged_in')) {
            abort(403, 'Akses ditolak. Silakan login sebagai staff.');
        }

        // ✅ Validate reservationId
        if (!is_numeric($reservationId) || $reservationId <= 0) {
            session()->flash('error', 'ID reservasi tidak valid.');
            return;
        }

        // ✅ Validate status enum
        $allowedStatuses = ['pending', 'confirmed', 'completed', 'canceled'];
        if (!in_array($status, $allowedStatuses, true)) {
            session()->flash('error', 'Status tidak valid.');
            return;
        }

        $reservation = Reservation::find($reservationId);
        if (!$reservation) {
            session()->flash('error', 'Reservasi tidak ditemukan.');
            return;
        }

    // Jika status diubah ke confirmed, validasi bentrok waktu
    if ($status === 'confirmed' && $reservation->status !== 'confirmed') {
        // Pastikan reservation_end_time tersedia
        if (!$reservation->reservation_end_time) {
            session()->flash('error', 'Reservasi tidak memiliki waktu selesai. Tidak dapat dikonfirmasi.');
            return;
        }

        $reservationStart = \Carbon\Carbon::parse($reservation->reservation_time);
        $reservationEnd = \Carbon\Carbon::parse($reservation->reservation_date->format('Y-m-d') . ' ' . $reservation->reservation_end_time);

        // Cek apakah ada reservasi lain yang sudah dikonfirmasi di meja yang sama dengan waktu yang bentrok
        $existingReservations = Reservation::where('table_id', $reservation->table_id)
            ->where('id', '!=', $reservationId)
            ->where('status', 'confirmed')
            ->where('reservation_date', $reservation->reservation_date)
            ->get();

        $conflictingReservation = null;
        foreach ($existingReservations as $existing) {
            $existingStart = \Carbon\Carbon::parse($existing->reservation_time);
            // Jika tidak ada reservation_end_time, gunakan durasi default 3 jam
            $existingEnd = $existing->reservation_end_time
                ? \Carbon\Carbon::parse($existing->reservation_date->format('Y-m-d') . ' ' . $existing->reservation_end_time)
                : $existingStart->copy()->addHours(3);

            // Check overlap: (start1 < end2) AND (end1 > start2)
            if ($existingStart->lt($reservationEnd) && $existingEnd->gt($reservationStart)) {
                $conflictingReservation = $existing;
                $conflictingReservation->calculated_end_time = $existingEnd;
                break;
            }
        }

        if ($conflictingReservation) {
            $startTime = \Carbon\Carbon::parse($conflictingReservation->reservation_time)->format('H:i');
            $endTime = $conflictingReservation->calculated_end_time
                ? $conflictingReservation->calculated_end_time->format('H:i')
                : \Carbon\Carbon::parse($conflictingReservation->reservation_end_time)->format('H:i');
            $timeRange = $startTime . ' - ' . $endTime;
            session()->flash('error', "Tidak dapat mengonfirmasi! Meja ini sudah direservasi pada {$timeRange}. Silakan batalkan atau selesaikan reservasi tersebut terlebih dahulu.");
            return;
        }
    }

    // Auto-set payment status to pending when confirming reservation
    if ($status === 'confirmed' && $reservation->payment_status === 'paid') {
        $reservation->payment_status = 'pending';
    }

    $reservation->status = $status;
    $reservation->save();
    }

    public function render()
    {
        $query = Reservation::with(['table', 'user', 'reservationItems.product']);

        if ($this->statusFilter && $this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Date filter
        if ($this->filterDate) {
            $query->whereDate('reservation_date', $this->filterDate);
        }

        // Use paginate instead of get to support pagination
        $reservations = $query->latest()->paginate(10);

        // Calculate revenue based on period
        $revenueToday = $this->calculateRevenueByPeriod(\Carbon\Carbon::today(), \Carbon\Carbon::today()->endOfDay());
        $revenueWeek = $this->calculateRevenueByPeriod(\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek());
        $revenueMonth = $this->calculateRevenueByPeriod(\Carbon\Carbon::now()->startOfMonth(), \Carbon\Carbon::now()->endOfMonth());
        $revenueYear = $this->calculateRevenueByPeriod(\Carbon\Carbon::now()->startOfYear(), \Carbon\Carbon::now()->endOfYear());

        return view('livewire.staff.reservation-management', [
            'reservations' => $reservations,
            'revenueToday' => $revenueToday['total'],
            'revenueTodayCount' => $revenueToday['count'],
            'revenueWeek' => $revenueWeek['total'],
            'revenueWeekCount' => $revenueWeek['count'],
            'revenueMonth' => $revenueMonth['total'],
            'revenueMonthCount' => $revenueMonth['count'],
            'revenueYear' => $revenueYear['total'],
            'revenueYearCount' => $revenueYear['count'],
        ]);
    }
}