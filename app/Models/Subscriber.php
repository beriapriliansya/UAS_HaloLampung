<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Untuk token

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'token',
        'verified_at',
        'is_subscribed',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'is_subscribed' => 'boolean',
    ];

    // Otomatis generate token saat membuat record (jika diperlukan untuk verifikasi)
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($subscriber) {
            if (empty($subscriber->token)) {
                $subscriber->token = Str::random(60); // Token unik untuk verifikasi/unsubscribe
            }
        });
    }
}