<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'payment_intent_id',
        'chat_id',
        'message_id',
        'user_id',
        'customer_id',  // Added to match the controller
        'amount',
        'status',
        'payment_date',
        'phone_number', // Added new field
        'address_line1', // Added new field
        'address_line2', // Added new field
        'city',         // Added new field
        'state',        // Added new field
        'postal_code',  // Added new field
        'address',      // Added new field for JSON representation
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'payment_date' => 'datetime',
        'amount' => 'float',
        'address' => 'array', // Cast the JSON address to an array when accessed
    ];

    /**
     * Get the user that made the payment.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the chat associated with the payment.
     */
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    /**
     * Get the message associated with the payment.
     */
    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    /**
     * Get formatted address as a string.
     *
     * @return string
     */
    public function getFormattedAddressAttribute()
    {
        $address = $this->address_line1;

        if (!empty($this->address_line2)) {
            $address .= ', ' . $this->address_line2;
        }

        $address .= ', ' . $this->city . ', ' . $this->state . ' ' . $this->postal_code;

        return $address;
    }

    public function rating(){
        return $this->hasOne(Rating::class, 'order_id');
    }
    public function pharmacy(){
        return $this->belongsTo(Pharmacy::class);
    }
}
