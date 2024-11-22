<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table = 'orders'; // Nome da tabela
    protected $primaryKey = 'id'; // Chave primária
    public $timestamps = true; // Para created_at e updated_at

    protected $fillable = [
        'tracking_number',
        'status',
        'estimated_delivery_date',
        'buy_date',
        'transaction_id',
    ];

    // Definição de constantes para status do pedido
    const STATUS_PROCESSING = 'Processing';
    const STATUS_SHIPPED = 'Shipped';
    const STATUS_DELIVERED = 'Delivered';

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
}
