<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

protected $fillable = ['table_id', 'status', 'rating', 'review', 'status_pembayaran', 'metode_pembayaran'];

    protected $appends = ['total_harga'];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getTotalHargaAttribute()
    {
        $total = 0;
        foreach ($this->orderItems as $item) {
            if ($item->product && $item->status_item !== 'dibatalkan') {
                $total += $item->product->harga * $item->qty;
            }
        }
        return $total;
    }
}
