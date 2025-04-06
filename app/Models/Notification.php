<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['pharmacy_id', 'post_id', 'read_at'];

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
