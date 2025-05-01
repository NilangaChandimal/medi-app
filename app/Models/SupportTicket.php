<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $fillable = [
        'subject',
        'message',
        'user_id',
        'user_type',
        'status',
        'admin_response',
        'admin_id'
    ];

    public function user()
    {
        return $this->morphTo();
    }
    public function admin()
{
    return $this->belongsTo(Admin::class); 
}
}
