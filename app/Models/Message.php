<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id', 'sender_id', 'sender_type', 'message', 'image','payment_button',
    ];

    public function sender()
    {
        return $this->morphTo();
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function getTotalAttribute()
{
    // Try to extract total from message text using regex
    preg_match('/Total: (\d+\.?\d*)/', $this->message, $matches);
    return isset($matches[1]) ? (float)$matches[1] : 0;
}
}
