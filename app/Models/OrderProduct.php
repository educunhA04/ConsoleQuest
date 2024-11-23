<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table = 'order_product'; // Nome da tabela
    protected $primaryKey = 'id'; // Chave primária
    public $timestamps = false; // Para created_at e updated_at

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
    ];

    /**
     * Relacionamento com Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relacionamento com Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
