<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_name',
        'identification_no',
        'phone_no',
        'email_address',
        'city',
        'cart_amount',
        'sale_amount',
        'payment_success',
    ];

    /**
     * Siparişin öğeleri ile ilişkisi
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Siparişin ürünleri ile ilişkisi - orderItems üzerinden
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items')
            ->withPivot('quantity', 'price', 'total')
            ->withTimestamps();
    }

    /**
     * Siparişin ödenip ödenmediğini belirten accessor
     */
    public function getIsPaidAttribute()
    {
        return $this->payment_success === 'yes';
    }

    /**
     * Ödenmiş siparişleri getiren scope
     */
    public function scopePaid($query)
    {
        return $query->where('payment_success', 'yes');
    }

    /**
     * Ödenmemiş siparişleri getiren scope
     */
    public function scopeUnpaid($query)
    {
        return $query->where('payment_success', 'no');
    }
}
