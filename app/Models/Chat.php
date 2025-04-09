<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'pharmacy_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }
    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
