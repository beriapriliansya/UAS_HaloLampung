<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    // Relasi Many-to-Many ke Destination
    public function destinations()
    {
        return $this->belongsToMany(Destination::class, 'destination_facility') // Nama tabel pivot
            ->withPivot('price', 'quota', 'is_available') // Kolom tambahan di pivot
            ->withTimestamps(); // Jika tabel pivot punya timestamps
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_facility')
            ->withPivot('quantity', 'price_at_booking')
            ->withTimestamps();
    }
}
