<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'kategori', 'harga', 'image_url', 'is_available'];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function setIsAvailableAttribute($value): void
    {
        $this->attributes['is_available'] = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? (bool) $value;
    }

    public function scopeAvailable($query)
    {
        return $query->whereRaw('"is_available" IS TRUE');
    }

    // Accessors for consistency with views
    public function getNameAttribute()
    {
        return $this->nama;
    }

    public function getPriceAttribute()
    {
        return $this->harga;
    }

    public function getImageAttribute()
    {
        return $this->image_url;
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getAverageRatingAttribute()
    {
        $avg = $this->orderItems()
            ->whereHas('order', function($q) {
                $q->whereNotNull('rating');
            })
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->avg('orders.rating');

        return $avg ?: 5.0;
    }

    public function getFormattedRatingAttribute()
    {
        return number_format($this->average_rating, 1, ',', '.');
    }

    public function getRatingCountAttribute()
    {
        return $this->orderItems()
            ->whereHas('order', function($q) {
                $q->whereNotNull('rating');
            })
            ->count();
    }
}
