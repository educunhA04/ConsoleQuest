<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'Order'; // Nome da tabela
    public $timestamps = false; // Para created_at e updated_at

    protected $fillable = [
        'user_id',
        'tracking_number',
        'status',
        'estimated_delivery_date',
        'buy_date',
        'shipping_address',
    ];

    protected $casts = [
        'buy_date' => 'datetime',
        'estimated_delivery_date' => 'datetime',
    ];
    

    // Definição de constantes para status do pedido
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';

    /**
     * Relacionamento com Transaction
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Relacionamento com OrderProduct
     */
    public function products()
    {
        return $this->hasMany(OrderProduct::class);
    }


    /**
     * Relationship with the User model
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with OrderProduct model
     */
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

}
