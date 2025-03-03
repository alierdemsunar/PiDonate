<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_uuid',
        'buyer_name',
        'identification_no',
        'phone_no',
        'email_address',
        'city',
        'cart_amount',
        'sale_amount',
        'card_number',
        'card_expiry_month',
        'card_expiry_year',
        'card_cvv',
        'payment_mpi_response',
        'payment_pos_response',
        'payment_success',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->order_uuid)) {
                $model->order_uuid = Str::uuid();
            }
        });
    }
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
