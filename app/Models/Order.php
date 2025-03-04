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
        'buyer_ip',
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
        'payment_mpi_enrollment_request_id',
        'payment_mpi_xid',
        'payment_mpi_cavv',
        'payment_mpi_eci',
        'payment_mpi_hash',
        'payment_mpi_error_code',
        'payment_mpi_error_message',
        'payment_mpi_response',
        'payment_pos_transaction_id',
        'payment_pos_result_code',
        'payment_pos_result_detail',
        'payment_pos_auth_code',
        'payment_pos_host_date',
        'payment_pos_rrn',
        'payment_pos_currency_amount',
        'payment_pos_response',
        'payment_3d_success',
        'payment_pos_success',
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
