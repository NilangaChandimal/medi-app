<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    protected $fillable = ['name', 'email', 'phone', 'address', 'city', 'profile_image', 'password', 'is_blocked', 'remember_token', 'verified_at'];

    public function pharmacy()
    {
        return $this->belongsToMany(Pharmacy::class);
    }
    public function pharmacyPosts()
    {
        return $this->belongsToMany(PharmacyPost::class);
    }
    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}


