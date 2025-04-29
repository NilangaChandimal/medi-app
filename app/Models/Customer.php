<?php

namespace App\Models;

use App\Notifications\CustomerResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'phone', 'address', 'city', 'profile_image', 'password', 'is_blocked', 'remember_token', 'verified_at'];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomerResetPasswordNotification($token));
    }
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
    public function rating(){
        return $this->hasMany(Rating::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function customerPosts()
    {
        return $this->hasMany(CustomerPost::class);
    }
}


