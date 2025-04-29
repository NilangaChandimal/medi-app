<?php

namespace App\Models;

use App\Notifications\AdminResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Add this import
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable // Extend the Authenticatable class
{
    use HasFactory, Notifiable;

    protected $table = 'admins';  // Make sure this matches your table name
    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = [
        'password',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPasswordNotification($token));
    }
}
