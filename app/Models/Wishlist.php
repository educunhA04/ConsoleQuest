<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = "wishlist";
    protected $fillable = ['id', 'user_id', 'product_id'];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');  
    }
}
