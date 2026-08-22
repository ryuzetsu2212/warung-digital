<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = ['nomor_meja', 'uuid', 'short_code', 'status_meja', 'active_session_token', 'qr_available'];

    /**
     * Generate a unique short code using Base62 encoding
     */
    public static function generateShortCode(): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $length = 6;
        
        do {
            $shortCode = '';
            for ($i = 0; $i < $length; $i++) {
                $shortCode .= $characters[random_int(0, strlen($characters) - 1)];
            }
        } while (self::where('short_code', $shortCode)->exists());
        
        return $shortCode;
    }

    /**
     * Boot method to auto-generate short_code on creation
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($table) {
            if (empty($table->short_code)) {
                $table->short_code = self::generateShortCode();
            }
        });
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
