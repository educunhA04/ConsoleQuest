<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Iluminate\Database\Eloquent\Relations\BelongsTo;


class Product extends Model
{

    public $timestamps = false;

    use HasFactory;

    protected $table = "product";

    protected $fillable = ['category_id', 'name', 'image', 'description', 'quantity', 'price', 'discount_percent'];

    protected $primaryKey = 'id';

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
