<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ShippingAddress extends Model
{
    use HasFactory;

    protected $table = 'shipping_address';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'postal_code',
        'address',
        'location',
        'country',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}