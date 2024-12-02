<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notification';
    public $timestamps  = false;

    protected $fillable = [
        'description',
        'viewed',
        'date',
    ];

    public function notificationUsers()
    {
        return $this->hasMany(NotificationUser::class, 'notification_id');
    }

    public function scopeUnviewed($query)
    {
        return $query->where('viewed', false);
    }
}
