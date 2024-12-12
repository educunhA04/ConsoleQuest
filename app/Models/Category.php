<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    // Disable automatic timestamps if they aren’t needed
    public $timestamps = false;

    // Define the primary key
    protected $primaryKey = 'id';

    // Set the table name if it's not the default 'categories'
    protected $table = 'category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Define the relationship to products
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}