<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'prescription_image', 'visibility',
        'description', 'status'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
