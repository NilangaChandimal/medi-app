<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'pharmacy_id',
        'content',
        'image',
        'video',
    ];

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class);
    }
}
