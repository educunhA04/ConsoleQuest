<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transaction'; 
    public $timestamps = false; 

    protected $fillable = [
        'code',
        'price',
        'NIF',
        'credit_card_number',
        'credit_card_exp_date',
        'credit_card_cvv',
    ];

    /**
     * Relacionamento com Order
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
