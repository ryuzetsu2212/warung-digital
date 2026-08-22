<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReservationPayment extends Component
{
    use WithFileUploads;
    public $reservation;

    public $paymentProof;

public function mount(Reservation $reservation)
{
    // ✅ SECURITY FIX: Authorization check with proper error message
    if (!Auth::check() || $reservation->user_id !== Auth::id()) {
        abort(403, 'Anda tidak memiliki akses ke reservasi ini.');
    }

    // ✅ Additional security: Check reservation status  
    if (!in_array($reservation->status, ['pending', 'confirmed'])) {
        abort(403, 'Reservasi ini tidak dapat diproses pembayaran.');
    }

    $this->reservation = $reservation;
    $this->paymentProof = null;

    // Set payment method to manual (Dana/OVO) and update status to dp_pending if still pending
    if ($this->reservation->payment_status === 'pending') {
        $this->reservation->update([
            'payment_status' => 'dp_pending',
            'payment_method' => 'manual',
        ]);
    }
}
    public function uploadPaymentProof()
    {
        // ✅ SECURITY FIX: Enhanced file validation
        $this->validate([
            'paymentProof' => [
                'required',
                'file',
                'mimes:jpeg,png,jpg',
                'max:2048', // 2MB
                'dimensions:min_width=100,min_height=100,max_width=4096,max_height=4096',
            ],
        ], [
            'paymentProof.required' => 'Bukti pembayaran wajib diunggah',
            'paymentProof.file' => 'File tidak valid',
            'paymentProof.mimes' => 'File harus berformat JPG, JPEG, atau PNG',
            'paymentProof.max' => 'Ukuran file maksimal 2MB',
            'paymentProof.dimensions' => 'Dimensi gambar harus antara 100x100 sampai 4096x4096 pixels',
        ]);

        // ✅ Additional MIME type verification (bypass prevention)
        $mimeType = $this->paymentProof->getMimeType();
        $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($mimeType, $allowedMimes)) {
            $this->addError('paymentProof', 'Tipe file tidak diizinkan.');
            return;
        }

        // ✅ Verify actual image content (not just extension)
        try {
            $imageInfo = getimagesize($this->paymentProof->getRealPath());
            if ($imageInfo === false) {
                $this->addError('paymentProof', 'File bukan gambar yang valid.');
                return;
            }
        } catch (\Exception $e) {
            $this->addError('paymentProof', 'File tidak dapat diproses.');
            return;
        }

        // ✅ Generate secure random filename
        $extension = $this->paymentProof->getClientOriginalExtension();
        $randomName = 'proof_' . $this->reservation->id . '_' . Str::random(32) . '.' . strtolower($extension);
        
        // ✅ Store with proper permissions
        $path = $this->paymentProof->storeAs('payment_proofs', $randomName, 'public');

        // ✅ Verify file was saved
        if (!$path) {
            $this->addError('paymentProof', 'Gagal menyimpan file. Silakan coba lagi.');
            return;
        }

        $this->reservation->update([
            'payment_proof' => $path,
            'payment_status' => 'dp_pending',
            'payment_time' => now(),
        ]);

        session()->flash('message', 'Bukti pembayaran berhasil diunggah. Tunggu konfirmasi dari staff.');
    }

    public function render()
    {
        return view('livewire.reservation-payment')->layout('components.layouts.app');
    }
}