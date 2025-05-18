<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Import Str
use App\Models\Booking; 
use App\Models\Facility;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'location',
        'operating_hours',
        'base_ticket_price',
        'visitor_quota',
        'main_photo',
    ];

    // Otomatis buat slug saat menyimpan nama
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($destination) {
            $destination->slug = Str::slug($destination->name);
            // Pastikan slug unik
            $count = static::where('slug', $destination->slug)->count();
            if ($count > 0) {
                // Jika sudah ada, tambahkan angka unik
                $destination->slug = $destination->slug . '-' . ($count + 1);
            }
        });

        static::updating(function ($destination) {
            // Jika nama berubah, update slug (hati-hati jika slug lama sudah terindeks)
            if ($destination->isDirty('name')) {
                $destination->slug = Str::slug($destination->name);
                $count = static::where('slug', $destination->slug)->where('id', '!=', $destination->id)->count();
                if ($count > 0) {
                    $destination->slug = $destination->slug . '-' . ($count + 1);
                }
            }
        });
    }

    // Relasi ke Fasilitas (One-to-Many)
    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'destination_facility') // Nama tabel pivot
            ->withPivot('price', 'quota', 'is_available') // Kolom tambahan di pivot
            ->withTimestamps()
            ->wherePivot('is_available', true); // Contoh: Hanya ambil yang available by default
    }

    // Jika ingin mengambil SEMUA relasi (termasuk yg not available) untuk admin
    public function allFacilitiesPivot()
    {
        return $this->belongsToMany(Facility::class, 'destination_facility')
            ->withPivot('id', 'price', 'quota', 'is_available') // Ambil ID pivot juga
            ->withTimestamps()
            ->orderBy('pivot_created_at', 'desc'); // Urutkan berdasarkan kapan ditambahkan
    }


    // Relasi ke Pemesanan (One-to-Many)
    //public function bookings()
    //{
        //return $this->hasMany(Booking::class);
    //}

    // Accessor untuk mendapatkan URL foto
    public function getMainPhotoUrlAttribute()
    {
        if ($this->main_photo) {
            // Sesuaikan path jika menggunakan storage disk yang berbeda
            return asset('storage/' . $this->main_photo);
        }
        // Return placeholder jika tidak ada foto
        return asset('images/placeholder.jpg'); // Buat gambar placeholder
    }

    public function bookings()
    {
        // Satu destinasi memiliki banyak booking
        return $this->hasMany(Booking::class);
    }
}
