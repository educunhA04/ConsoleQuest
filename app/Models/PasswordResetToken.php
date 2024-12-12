<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    protected $table = 'password_reset_tokens';  

    public $incrementing = false;  
    public $timestamps = false;    

    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];

    protected $primaryKey = ['email', 'token'];

}

