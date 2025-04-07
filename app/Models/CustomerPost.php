<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'content',
        'image',
        'video',
        'visibility',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }
}
