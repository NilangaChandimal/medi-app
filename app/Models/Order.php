<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'pharmacy_id', 'post_id',
        'status', 'total_amount'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }
}
