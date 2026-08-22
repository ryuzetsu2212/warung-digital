<?php

namespace App\Livewire;

use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\Table;
use App\Models\Product;
use App\Models\Order;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationCreate extends Component
{
    public $table_id;
    public $reservation_date;
    public $reservation_time;
    public $reservation_end_time;
    public $guest_count;
    public $notes = '';
    public $status = 'pending';

    // Menu selection
    public $selectedItems = [];
    public $products = [];
    public $search = '';
    
    // Customer profile
    public $reservationCount = 0;
    public $recentReservations = [];
    
    // All confirmed reservations for public viewing
    public $confirmedReservations = [];
    
    // Edit profile properties
    public $editMode = false;
    public $editName = '';
    public $editPhone = '';
    public $editPassword = '';
    public $editPassword_confirmation = '';

    protected $rules = [
        'table_id' => 'required|exists:tables,id',
        'reservation_date' => 'required|date|after_or_equal:today',
        'reservation_time' => 'required',
        'reservation_end_time' => 'required|after:reservation_time',
        'guest_count' => 'required|integer|min:1|max:20',
        'editName' => 'required|string|max:255',
        'editPhone' => 'required|string|max:15',
        'editPassword' => 'nullable|string|min:8|confirmed',
    ];

    protected $messages = [
        'table_id.required' => 'Silakan pilih meja terlebih dahulu.',
        'table_id.exists' => 'Meja yang dipilih tidak valid.',
        'reservation_date.required' => 'Tanggal reservasi harus diisi.',
        'reservation_date.after_or_equal' => 'Tanggal reservasi harus hari ini atau setelahnya.',
        'reservation_time.required' => 'Waktu mulai reservasi harus diisi.',
        'reservation_end_time.required' => 'Waktu selesai reservasi harus diisi.',
        'reservation_end_time.after' => 'Waktu selesai harus lebih besar dari waktu mulai.',
        'guest_count.required' => 'Jumlah tamu harus diisi.',
        'guest_count.min' => 'Jumlah tamu minimal 1 orang.',
        'guest_count.max' => 'Jumlah tamu maksimal 20 orang.',
        'editName.required' => 'Nama harus diisi.',
        'editName.max' => 'Nama maksimal 255 karakter.',
        'editPhone.required' => 'Nomor telepon harus diisi.',
        'editPhone.max' => 'Nomor telepon maksimal 15 karakter.',
        'editPassword.min' => 'Password minimal 8 karakter.',
        'editPassword.confirmed' => 'Konfirmasi password tidak cocok.',
    ];

    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('customer.login');
        }

        $this->loadProducts();
        $this->loadCustomerProfile();
    }

    public function loadCustomerProfile()
    {
        $user = Auth::user();
        
        // Get total reservation count (exclude customer-deleted ones)
        $this->reservationCount = Reservation::where('user_id', $user->id)
            ->whereNull('customer_deleted_at')
            ->count();
        
        // Get recent reservations (last 3, exclude customer-deleted ones)
        $this->recentReservations = Reservation::where('user_id', $user->id)
            ->whereNull('customer_deleted_at')
            ->with('table')
            ->latest('reservation_date')
            ->latest('reservation_time')
            ->take(3)
            ->get();
        
        // Get all confirmed reservations (for public viewing)
        $this->loadConfirmedReservations();
        
        // Initialize edit fields with current user data
        $this->editName = $user->name;
        $this->editPhone = $user->phone ?? '';
        $this->editPassword = '';
        $this->editPassword_confirmation = '';
    }
    
    public function loadConfirmedReservations()
    {
        // Get all confirmed reservations from today onwards
        $this->confirmedReservations = Reservation::where('status', 'confirmed')
            ->where('reservation_date', '>=', Carbon::today())
            ->with('table')
            ->orderBy('reservation_date')
            ->orderBy('reservation_time')
            ->get();
    }

    public function loadProducts()
    {
        $query = Product::where('is_available', true);
        if ($this->search) {
            $query->where('nama', 'like', '%' . $this->search . '%');
        }
        $this->products = $query->get();
    }

    public function updatedSearch()
    {
        $this->loadProducts();
    }


    public function incrementQuantity($productId)
    {
        if (!isset($this->selectedItems[$productId])) {
            $this->selectedItems[$productId] = 0;
        }
        $this->selectedItems[$productId]++;
    }

    public function decrementQuantity($productId)
    {
        if (!isset($this->selectedItems[$productId])) {
            $this->selectedItems[$productId] = 0;
        }
        if ($this->selectedItems[$productId] > 0) {
            $this->selectedItems[$productId]--;
        }
    }


    public function submit()
    {
        $this->validate();

        $user = Auth::user();

        // Check if table exists
        $table = Table::find($this->table_id);
        if (!$table) {
            session()->flash('error', 'Meja tidak ditemukan.');
            return;
        }

        // Check if table has active reservations for the selected date/time
        $reservationDateTime = Carbon::parse($this->reservation_date . ' ' . $this->reservation_time);
        $reservationEndDateTime = Carbon::parse($this->reservation_date . ' ' . $this->reservation_end_time);

        // Check if end time is after 22:30 (10:30 PM) - batas akhir reservasi
        if ($reservationEndDateTime->format('H:i') > '22:30') {
            session()->flash('error', 'Reservasi hanya tersedia hingga jam 22:30 (10:30 malam). Silakan pilih waktu selesai sebelum jam 22:30.');
            return;
        }

        // Warning if end time is after 22:00 (10 PM)
        if ($reservationEndDateTime->format('H:i') > '22:00') {
            session()->flash('warning', 'Perhatian: Reservasi Anda mendekati jam tutup (23:00). Pastikan Anda dapat hadir tepat waktu.');
        }

        // Get all confirmed/pending reservations on the same table and date
        $existingReservations = Reservation::where('table_id', $this->table_id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('reservation_date', $this->reservation_date)
            ->get();

        $conflictingReservation = null;
        foreach ($existingReservations as $existing) {
            $existingStart = Carbon::parse($existing->reservation_date->format('Y-m-d') . ' ' . $existing->reservation_time);
            // Jika tidak ada reservation_end_time, gunakan durasi default 3 jam
            $existingEnd = $existing->reservation_end_time
                ? Carbon::parse($existing->reservation_date->format('Y-m-d') . ' ' . $existing->reservation_end_time)
                : $existingStart->copy()->addHours(3);

            // Check overlap: (start1 < end2) AND (end1 > start2)
            if ($existingStart->lt($reservationEndDateTime) && $existingEnd->gt($reservationDateTime)) {
                $conflictingReservation = $existing;
                $conflictingReservation->calculated_end_time = $existingEnd;
                break;
            }
        }

        if ($conflictingReservation) {
            $startTime = Carbon::parse($conflictingReservation->reservation_time)->format('H:i');
            $endTime = $conflictingReservation->calculated_end_time
                ? $conflictingReservation->calculated_end_time->format('H:i')
                : Carbon::parse($conflictingReservation->reservation_end_time)->format('H:i');
            $timeRange = $startTime . ' - ' . $endTime;
            session()->flash('error', "Meja ini sudah direservasi pada {$timeRange}. Silakan pilih waktu atau meja lain.");
            return;
        }

        // Hitung total amount dari menu yang dipilih
        $totalAmount = $this->calculateTotal();
        $dpAmount = $this->calculateDpAmount($totalAmount);

        // Create reservation
        $reservation = Reservation::create([
            'user_id' => $user->id,
            'table_id' => $this->table_id,
            'customer_name' => $user->name,
            'phone_number' => $user->phone ?? '-',
            'reservation_date' => $this->reservation_date,
            'reservation_time' => $this->reservation_time,
            'reservation_end_time' => $this->reservation_end_time,
            'number_of_guests' => $this->guest_count,
            'status' => 'pending',
            'notes' => $this->notes,
            'total_amount' => $totalAmount,
            'dp_amount' => $dpAmount,
            'payment_status' => 'pending',
        ]);

        // Create reservation items if any selected
        $selectedItems = array_filter($this->selectedItems, function($qty) {
            return $qty > 0;
        });

        foreach ($selectedItems as $productId => $qty) {
            $product = Product::find($productId);
            if ($product) {
                ReservationItem::create([
                    'reservation_id' => $reservation->id,
                    'product_id' => $productId,
                    'qty' => $qty,
                    'price' => $product->harga,
                ]);
            }
        }

         // Payment processing removed - use cash only

        session()->flash('success', 'Reservasi berhasil dibuat! Silakan lakukan pembayaran DP untuk mengkonfirmasi reservasi Anda.');
        $this->redirect(route('customer.reservations'), navigate: true);
    }

    public function toggleEditMode()
    {
        $this->editMode = !$this->editMode;
        
        if ($this->editMode) {
            // Reset edit fields to current user data
            $user = Auth::user();
            $this->editName = $user->name;
            $this->editPhone = $user->phone ?? '';
            $this->editPassword = '';
            $this->editPassword_confirmation = '';
        }
    }
    
    public function updateProfile()
    {
        $this->validate([
            'editName' => 'required|string|max:255',
            'editPhone' => 'required|string|max:15',
            'editPassword' => 'nullable|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        $user->name = $this->editName;
        $user->phone = $this->editPhone;
        
        // Update password only if provided
        if (!empty($this->editPassword)) {
            $user->password = bcrypt($this->editPassword);
        }
        
        $user->save();
        
        $this->editMode = false;
        $this->editPassword = '';
        $this->editPassword_confirmation = '';
        session()->flash('success', 'Profil berhasil diperbarui!');
        
        // Reload profile data
        $this->loadCustomerProfile();
    }
    
    public function cancelEdit()
    {
        $this->editMode = false;
        $user = Auth::user();
        $this->editName = $user->name;
        $this->editPhone = $user->phone ?? '';
        $this->editPassword = '';
        $this->editPassword_confirmation = '';
    }
    
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        
        return redirect('/');
    }

    public function calculateTotal()
    {
        $total = 0;
        foreach ($this->selectedItems as $productId => $quantity) {
            if ($quantity > 0) {
                $product = $this->products->firstWhere('id', $productId);
                if ($product) {
                    $total += $product->harga * $quantity;
                }
            }
        }
        return $total;
    }

    public function calculateDpAmount($total)
    {
        // DP 50% dari total
        return $total * 0.5;
    }

    public function render()
    {
        // Get all tables and mark which ones are occupied
        $tables = Table::all()->map(function ($table) {
            $table->is_occupied = $table->status_meja === 'terisi';
            return $table;
        });
        
        return view('livewire.reservation-create', compact('tables'));
    }
}
