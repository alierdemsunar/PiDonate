<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'image',
        'description',
        'price',
        'sale_price',
        'status'
    ];

    /**
     * Ürünün aktif (satışta) olup olmadığını belirten accessor
     */
    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }

    /**
     * Ürünün indirimli olup olmadığını belirten accessor
     */
    public function getHasDiscountAttribute()
    {
        return $this->sale_price > 0 && $this->sale_price < $this->price;
    }

    /**
     * Ürünün gerçek fiyatını belirten accessor (indirimli fiyat varsa onu kullanır)
     */
    public function getActualPriceAttribute()
    {
        return $this->sale_price > 0 ? $this->sale_price : $this->price;
    }

    /**
     * Ürünün sipariş öğeleri ile ilişkisi
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Ürünün siparişleri - orderItems üzerinden
     */
    public function orders()
    {
        return $this->hasManyThrough(Order::class, OrderItem::class, 'product_id', 'id', 'id', 'order_id');
    }

    /**
     * Aktif ürünleri getiren scope
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * İndirimli ürünleri getiren scope
     */
    public function scopeDiscounted($query)
    {
        return $query->where('sale_price', '>', 0)->whereColumn('sale_price', '<', 'price');
    }
}
