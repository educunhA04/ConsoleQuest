<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'Order'; // Table name
    public $timestamps = false; // Disable created_at and updated_at timestamps

    protected $fillable = [
        'user_id',
        'tracking_number',
        'status',
        'estimated_delivery_date',
        'buy_date',
        'postal_code',
        'address',
        'location',
        'country',
    ];

    protected $casts = [
        'buy_date' => 'datetime',
        'estimated_delivery_date' => 'datetime',
    ];

    // Status constants
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

  
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }
    public function products()
{
    return $this->hasMany(OrderProduct::class, 'order_id');
}

  
    public function getFullShippingAddressAttribute()
    {
        return "{$this->address}, {$this->postal_code}, {$this->location}, {$this->country}";
    }


    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
