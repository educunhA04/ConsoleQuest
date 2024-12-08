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


    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(OrderProduct::class);return $this->hasManyThrough(
            Product::class,
            OrderProduct::class,
            'order_id',      // Foreign key on OrderProduct table
            'id',            // Foreign key on Product table
            'id',            // Local key on Order table
            'product_id'     // Local key on OrderProduct table
        );
    }

}
