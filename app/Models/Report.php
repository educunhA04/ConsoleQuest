<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'report';

    protected $fillable = ['review_id', 'user_id', 'reason', 'description'];

    protected $primaryKey = 'id'; 

    public function review()
    {
        return $this->belongsTo(Review::class, 'review_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
