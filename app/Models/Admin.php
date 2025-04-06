<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Add this import
use Illuminate\Database\Eloquent\Model;

class Admin extends Authenticatable // Extend the Authenticatable class
{
    protected $table = 'admins';  // Make sure this matches your table name
    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = [
        'password',
    ];
}
