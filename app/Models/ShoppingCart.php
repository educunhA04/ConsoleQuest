<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = "shopping_cart";
    protected $fillable = ['id', 'user_id', 'product_id','quantity'];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');  // Belongs to product based on product_id
    }
}
