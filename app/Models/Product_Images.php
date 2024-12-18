<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Product_Images extends Model
{
    use HasFactory;

    protected $table = 'product_images';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'url',
        'product_id',
    ];

 
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
