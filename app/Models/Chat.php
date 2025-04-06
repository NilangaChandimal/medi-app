<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'customer_id', 'pharmacy_id'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
