<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Untuk UUID

class Ticket extends Model
{
    use HasFactory;

    // Status Constants
    const STATUS_VALID = 'valid';
    const STATUS_USED = 'used';
    const STATUS_EXPIRED = 'expired'; // Jika tanggal kunjungan lewat dan belum dipakai?
    const STATUS_CANCELLED = 'cancelled'; // Jika booking dibatalkan

    protected $fillable = [
        'booking_id',
        'ticket_code',
        'status',
        'checked_in_at',
        'checked_in_by',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];

    // Otomatis generate UUID saat membuat tiket
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($ticket) {
            if (empty($ticket->ticket_code)) {
                // Pastikan kolom ticket_code di DB adalah tipe UUID atau String yg cukup panjang
                $ticket->ticket_code = (string) Str::uuid();
            }
        });
    }

    // Relasi ke Booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Relasi ke User yang melakukan check-in (opsional)
    public function checker()
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }

     // --- Accessors ---
    public function getStatusLabelAttribute(): string
    {
        switch ($this->status) {
            case self::STATUS_VALID: return 'Valid';
            case self::STATUS_USED: return 'Sudah Digunakan';
            case self::STATUS_EXPIRED: return 'Kadaluarsa';
            case self::STATUS_CANCELLED: return 'Dibatalkan';
            default: return ucfirst($this->status);
        }
    }

    public function getStatusBadgeClassAttribute(): string
    {
        switch ($this->status) {
            case self::STATUS_VALID: return 'bg-success';
            case self::STATUS_USED: return 'bg-secondary';
            case self::STATUS_EXPIRED: return 'bg-warning text-dark';
            case self::STATUS_CANCELLED: return 'bg-danger';
            default: return 'bg-light text-dark';
        }
    }

    // Apakah tiket bisa di check-in?
    public function canBeCheckedIn(): bool
    {
        // Hanya valid jika status 'valid' dan tanggal kunjungan sesuai (misal hari ini)
        return $this->status === self::STATUS_VALID &&
               $this->booking?->booking_date?->isToday(); // Perlu cek null safety
    }
}