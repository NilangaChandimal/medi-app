<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactAdmin extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'message', 'responded_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
