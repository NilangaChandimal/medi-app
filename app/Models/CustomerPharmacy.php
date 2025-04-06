<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPharmacy extends Model
{
    use HasFactory;

    protected $table = 'customer_pharmacy';

    protected $fillable = ['customer_id', 'pharmacy_id'];
}
