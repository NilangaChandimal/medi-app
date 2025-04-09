<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pharmacy extends Authenticatable
{

    protected $fillable = [
        'name', 'email', 'registration_number', 'license_details',
        'address', 'phone', 'city', 'is_blocked', 'status', 'profile_image', 'password', 'remember_token', 'verified_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_blocked' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class);
    }
    public function pharmacyPosts()
    {
        return $this->hasMany(PharmacyPost::class);
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function ratings()
{
    return $this->hasMany(Rating::class);
}

public function averageRating()
{
    return $this->ratings()->avg('rating');
}

public function totalRatings()
{
    return $this->ratings()->count();
}
}
