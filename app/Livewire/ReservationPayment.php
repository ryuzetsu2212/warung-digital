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

        // ✅ Generate secure random filename with WebP conversion
        $randomName = 'proof_' . $this->reservation->id . '_' . Str::random(32) . '.webp';

        // ✅ Convert to WebP for smaller file size
        $tempPath = sys_get_temp_dir() . '/proof_' . Str::random(16) . '.webp';
        try {
            $img = null;
            switch ($mimeType) {
                case 'image/jpeg':
                    $img = imagecreatefromjpeg($this->paymentProof->getRealPath());
                    break;
                case 'image/png':
                    $img = imagecreatefrompng($this->paymentProof->getRealPath());
                    break;
            }
            if ($img === null) {
                throw new \Exception('Gagal decode gambar.');
            }
            imagewebp($img, $tempPath, 85);
            imagedestroy($img);
        } catch (\Throwable $e) {
            @unlink($tempPath);
            $this->addError('paymentProof', 'Gagal memproses gambar: ' . $e->getMessage());
            return;
        }

        // ✅ Upload to Supabase Storage (persistent, public bucket)
        $client = new \GuzzleHttp\Client();
        $supabaseUrl = env('SUPABASE_PROJECT_URL');
        $supabaseKey = env('SUPABASE_SERVICE_ROLE_KEY');
        if (!$supabaseUrl || !$supabaseKey) {
            $this->addError('paymentProof', 'Konfigurasi server belum lengkap.');
            return;
        }

        try {
            $response = $client->request('POST', "$supabaseUrl/storage/v1/object/payment-proofs/{$randomName}", [
                'headers' => [
                    'Authorization' => "Bearer {$supabaseKey}",
                    'apikey' => $supabaseKey,
                ],
                'body' => fopen($tempPath, 'r'),
            ]);
            $status = json_decode((string) $response->getBody(), true);
            if (!isset($status['Key']) && $response->getStatusCode() !== 200 && $response->getStatusCode() !== 204) {
                throw new \Exception('Upload failed');
            }
        } catch (\Throwable $e) {
            @unlink($tempPath);
            $this->addError('paymentProof', 'Gagal mengunggah file: ' . $e->getMessage());
            return;
        }
        @unlink($tempPath);

        $this->reservation->update([
            'payment_proof' => "https://pbaqettpfiqpxsccuiox.supabase.co/storage/v1/object/public/payment-proofs/{$randomName}",
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