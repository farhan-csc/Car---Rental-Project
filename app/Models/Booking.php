<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    // Tambahkan kode ini agar Laravel mengizinkan pengisian data
    protected $fillable = [
        'booking_code',
        'user_id',
        'guest_name',
        'guest_phone',
        'guest_email',
        'car_id',
        'pickup_at',
        'return_due_at',
        'rental_days',
        'price_per_day',
        'total_amount',
        'payment_option',
        'dp_amount',
        'status',
        'payment_deadline',
        'payment_token',
        'policy_accepted_at',
        'picked_up_at',   // <-- TAMBAHKAN INI
        'returned_at',    // <-- TAMBAHKAN INI
        'pickup_note',    // <-- TAMBAHKAN INI
        'return_note',    // <-- TAMBAHKAN INI
        'late_days',      // <-- TAMBAHKAN INI
        'late_fee',       // <-- TAMBAHKAN INI
        'cancel_reason',  // <-- TAMBAHKAN INI
        'cancelled_at',   // <-- TAMBAHKAN INI
        'refund_amount',  // <-- TAMBAHKAN INI
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}