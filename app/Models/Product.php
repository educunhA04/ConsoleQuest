<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;  // Corrected the typo here
use App\Models\Category;

class Product extends Model
{
    public $timestamps = false;

    use HasFactory;

    protected $table = "product";

    protected $fillable = ['category_id', 'name', 'image', 'description', 'quantity', 'price', 'discount_percent'];

    protected $primaryKey = 'id';

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class)->whereNotNull('id');
        
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }



}



