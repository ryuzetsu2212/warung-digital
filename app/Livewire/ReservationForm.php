<?php

namespace App\Livewire;

use App\Models\Reservation;
use App\Models\Table;
use App\Models\Order;
use Livewire\Component;
use Carbon\Carbon;

class ReservationForm extends Component
{
    public $table_id;
    public $customer_name;
    public $phone_number;
    public $reservation_date;
    public $reservation_time;
    public $number_of_guests;
    public $status = 'pending';

    public $isSubmitted = false;
    public $submittedData = null;
    public $tableWarning = null;
    public $availableTables = [];

    protected $rules = [
        'table_id' => 'required|exists:tables,id',
        'customer_name' => 'required|string|max:255',
        'phone_number' => 'required|string|max:20',
        'reservation_date' => 'required|date|after_or_equal:today',
        'reservation_time' => 'required',
        'number_of_guests' => 'required|integer|min:1',
    ];

    public function updated($propertyName)
    {
        // Check table availability when table, date, or time changes
        if (in_array($propertyName, ['table_id', 'reservation_date', 'reservation_time'])) {
            $this->checkTableAvailability();
        }
    }

    public function checkTableAvailability()
    {
        $this->tableWarning = null;
        $this->availableTables = [];

        if (!$this->table_id || !$this->reservation_date || !$this->reservation_time) {
            return;
        }

        $selectedTable = Table::find($this->table_id);
        if (!$selectedTable) {
            return;
        }

        // Ensure we have just the date part, not datetime
        $dateOnly = Carbon::parse($this->reservation_date)->format('Y-m-d');
        $reservationDateTime = Carbon::parse($dateOnly . ' ' . $this->reservation_time);
        $isToday = Carbon::parse($this->reservation_date)->isToday();

        // Check 1: Is the table currently occupied?
        if ($isToday && $selectedTable->status_meja === 'terisi') {
            $this->tableWarning = "⚠️ Meja {$selectedTable->nomor_meja} saat ini sedang ditempati pengunjung.";
            $this->suggestAlternativeTables($reservationDateTime);
            return;
        }

        // Check 2: Does the table have an existing reservation at this time?
        $conflictingReservation = Reservation::where('table_id', $this->table_id)
            ->where('reservation_date', $this->reservation_date)
            ->where('status', '!=', 'canceled')
            ->get()
            ->filter(function ($reservation) use ($reservationDateTime) {
                $existingDateOnly = Carbon::parse($reservation->reservation_date)->format('Y-m-d');
                $existingTimeOnly = Carbon::parse($reservation->reservation_time)->format('H:i:s');
                $existingTime = Carbon::parse($existingDateOnly . ' ' . $existingTimeOnly);
                // Check if within 2 hours of each other (typical meal duration)
                return abs($existingTime->diffInMinutes($reservationDateTime)) < 120;
            })
            ->first();

        if ($conflictingReservation) {
            $conflictTime = Carbon::parse($conflictingReservation->reservation_time)->format('H:i');
            $this->tableWarning = "⚠️ Meja {$selectedTable->nomor_meja} sudah direservasi oleh {$conflictingReservation->customer_name} pada pukul {$conflictTime}.";
            $this->suggestAlternativeTables($reservationDateTime);
            return;
        }

        // Check 3: Is there an active order on this table for today?
        if ($isToday) {
            $activeOrder = Order::where('table_id', $this->table_id)
                ->whereDate('created_at', today())
                ->whereIn('status', ['pending', 'proses'])
                ->exists();

            if ($activeOrder) {
                $this->tableWarning = "⚠️ Meja {$selectedTable->nomor_meja} memiliki pesanan aktif saat ini.";
                $this->suggestAlternativeTables($reservationDateTime);
                return;
            }
        }
    }

    private function suggestAlternativeTables($reservationDateTime)
    {
        $allTables = Table::all();
        $this->availableTables = [];

        foreach ($allTables as $table) {
            if ($table->id == $this->table_id) {
                continue;
            }

            $isAvailable = true;

            // Check if table is currently occupied
            if (Carbon::parse($this->reservation_date)->isToday() && $table->status_meja === 'terisi') {
                $isAvailable = false;
            }

            // Check for conflicting reservations
            $hasConflict = Reservation::where('table_id', $table->id)
                ->where('reservation_date', $this->reservation_date)
                ->where('status', '!=', 'canceled')
                ->get()
                ->filter(function ($reservation) use ($reservationDateTime) {
                    $existingDateOnly = Carbon::parse($reservation->reservation_date)->format('Y-m-d');
                    $existingTimeOnly = Carbon::parse($reservation->reservation_time)->format('H:i:s');
                    $existingTime = Carbon::parse($existingDateOnly . ' ' . $existingTimeOnly);
                    return abs($existingTime->diffInMinutes($reservationDateTime)) < 120;
                })
                ->isNotEmpty();

            if ($hasConflict) {
                $isAvailable = false;
            }

            // Check for active orders
            if (Carbon::parse($this->reservation_date)->isToday()) {
                $hasActiveOrder = Order::where('table_id', $table->id)
                    ->whereDate('created_at', today())
                    ->whereIn('status', ['pending', 'proses'])
                    ->exists();

                if ($hasActiveOrder) {
                    $isAvailable = false;
                }
            }

            if ($isAvailable) {
                $this->availableTables[] = $table;
            }
        }
    }

    public function submit()
    {
        $this->validate();

        // Final check before creating reservation
        $this->checkTableAvailability();
        if ($this->tableWarning) {
            $this->addError('table_id', 'Meja yang dipilih tidak tersedia. Silakan pilih meja lain.');
            return;
        }

        $reservation = Reservation::create([
            'table_id' => $this->table_id,
            'customer_name' => $this->customer_name,
            'phone_number' => $this->phone_number,
            'reservation_date' => $this->reservation_date,
            'reservation_time' => $this->reservation_time,
            'number_of_guests' => $this->number_of_guests,
            'status' => $this->status,
        ]);

        $this->submittedData = $reservation->load('table');
        $this->isSubmitted = true;
    }

    public function resetForm()
    {
        $this->reset(['table_id', 'customer_name', 'phone_number', 'reservation_date', 'reservation_time', 'number_of_guests', 'isSubmitted', 'submittedData', 'tableWarning', 'availableTables']);
    }

    public function render()
    {
        $tables = Table::all();
        return view('livewire.reservation-form', compact('tables'));
    }
}
