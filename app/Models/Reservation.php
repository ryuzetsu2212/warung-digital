<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'table_id', 'customer_name', 'phone_number', 'reservation_date', 'reservation_time', 'reservation_end_time', 'reserved_until', 'number_of_guests', 'status', 'notes', 'total_amount', 'dp_amount', 'payment_status', 'payment_type', 'payment_time', 'payment_proof'];

    protected $casts = [
        'reservation_date' => 'date',
        'reservation_time' => 'datetime',
        'reserved_until' => 'datetime',
        'payment_time' => 'datetime',
        'total_amount' => 'decimal:2',
        'dp_amount' => 'decimal:2',
    ];

    protected static function booted()
    {
        // Ketika reservasi dikonfirmasi, set meja menjadi terisi dan hitung waktu berakhir (3 jam)
        static::updating(function ($reservation) {
            if ($reservation->isDirty('status') && $reservation->status === 'confirmed') {
                // Kombinasi tanggal dan waktu reservasi
                $reservationDateTime = Carbon::parse($reservation->reservation_date->format('Y-m-d') . ' ' . $reservation->reservation_time->format('H:i:s'));
                
                // Set waktu berakhir 3 jam dari waktu reservasi
                $reservation->reserved_until = $reservationDateTime->copy()->addHours(3);
                
                // Update status meja menjadi terisi - gunakan table() method dengan ()
                $tableModel = \App\Models\Table::find($reservation->table_id);
                if ($tableModel) {
                    $tableModel->update([
                        'status_meja' => 'terisi',
                        'qr_available' => false
                    ]);
                }
            }

            // Ketika reservasi dibatalkan atau selesai, kembalikan status meja
            if ($reservation->isDirty('status') && in_array($reservation->status, ['canceled', 'completed'])) {
                $tableModel = \App\Models\Table::find($reservation->table_id);
                if ($tableModel) {
                    $tableModel->update([
                        'status_meja' => 'tersedia',
                        'qr_available' => true,
                        'active_session_token' => null
                    ]);
                }
            }
        });
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reservationItems()
    {
        return $this->hasMany(ReservationItem::class);
    }

    /**
     * Cek apakah reservasi sudah melewati batas waktu 3 jam
     */
    public function isExpired()
    {
        if (!$this->reserved_until) {
            return false;
        }

        return now()->greaterThan($this->reserved_until);
    }

    /**
     * Scope untuk mendapatkan reservasi aktif yang sudah expired
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'confirmed')
            ->whereNotNull('reserved_until')
            ->where('reserved_until', '<', now());
    }
}
