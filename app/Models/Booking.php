<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Untuk generate booking code

class Booking extends Model
{
    use HasFactory;

    // Status Constants (atau gunakan Enum di PHP 8.1+)
    const STATUS_PENDING_PAYMENT = 'pending_payment';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed'; // Setelah kunjungan selesai
    const STATUS_FAILED = 'failed'; // Jika pembayaran gagal total

    const PAYMENT_PENDING = 'pending';
    const PAYMENT_SUCCESS = 'success';
    const PAYMENT_FAILURE = 'failure';
    const PAYMENT_EXPIRED = 'expired';
    const PAYMENT_CANCELLED = 'cancelled'; // Jika dibatalkan manual

    protected $fillable = [
        'booking_code',
        'user_id',
        'destination_id',
        'booking_date',
        'num_tickets',
        'base_ticket_price_at_booking',
        'total_facility_price',
        'total_amount',
        'payment_method',
        'payment_status',
        'payment_id',
        'notes',
        'status',
    ];

    protected $casts = [
        'booking_date' => 'date', // Casting ke objek Carbon Date
        'base_ticket_price_at_booking' => 'decimal:2',
        'total_facility_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    // Generate Booking Code saat membuat record baru
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = static::generateBookingCode();
            }
        });
    }

    // Helper untuk generate kode unik
    public static function generateBookingCode()
    {
        // Contoh: INV/YYYYMMDD/5_CHAR_RANDOM
        $datePart = now()->format('Ymd');
        $randomPart = strtoupper(Str::random(5));
        $code = "INV/{$datePart}/{$randomPart}";

        // Pastikan unik (meski kemungkinannya kecil)
        while (static::where('booking_code', $code)->exists()) {
            $randomPart = strtoupper(Str::random(5));
            $code = "INV/{$datePart}/{$randomPart}";
        }
        return $code;
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Destination
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    // Relasi ke Payment (jika sudah dibuat)
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Relasi ke Facilities (Many-to-Many)
    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'booking_facility')
            ->withPivot('quantity', 'price_at_booking')
            ->withTimestamps();
    }

    // --- Accessors (Contoh) ---

    // Mendapatkan label status yang lebih ramah
    public function getStatusLabelAttribute(): string
    {
        switch ($this->status) {
            case self::STATUS_PENDING_PAYMENT:
                return 'Menunggu Pembayaran';
            case self::STATUS_CONFIRMED:
                return 'Terkonfirmasi';
            case self::STATUS_CANCELLED:
                return 'Dibatalkan';
            case self::STATUS_COMPLETED:
                return 'Selesai';
            case self::STATUS_FAILED:
                return 'Gagal';
            default:
                return ucfirst($this->status);
        }
    }

    // Mendapatkan kelas Bootstrap untuk status
    public function getStatusBadgeClassAttribute(): string
    {
        switch ($this->status) {
            case self::STATUS_PENDING_PAYMENT:
                return 'bg-warning text-dark';
            case self::STATUS_CONFIRMED:
                return 'bg-success';
            case self::STATUS_CANCELLED:
                return 'bg-secondary';
            case self::STATUS_COMPLETED:
                return 'bg-info text-dark';
            case self::STATUS_FAILED:
                return 'bg-danger';
            default:
                return 'bg-light text-dark';
        }
    }

    // Mendapatkan label status pembayaran
    public function getPaymentStatusLabelAttribute(): string
    {
        switch ($this->payment_status) {
            case self::PAYMENT_PENDING:
                return 'Pending';
            case self::PAYMENT_SUCCESS:
                return 'Sukses';
            case self::PAYMENT_FAILURE:
                return 'Gagal';
            case self::PAYMENT_EXPIRED:
                return 'Kadaluarsa';
            case self::PAYMENT_CANCELLED:
                return 'Dibatalkan';
            default:
                return ucfirst($this->payment_status);
        }
    }

    // Mendapatkan kelas Bootstrap untuk status pembayaran
    public function getPaymentStatusBadgeClassAttribute(): string
    {
        switch ($this->payment_status) {
            case self::PAYMENT_PENDING:
                return 'bg-warning text-dark';
            case self::PAYMENT_SUCCESS:
                return 'bg-success';
            case self::PAYMENT_FAILURE:
                return 'bg-danger';
            case self::PAYMENT_EXPIRED:
                return 'bg-secondary';
            case self::PAYMENT_CANCELLED:
                return 'bg-dark';
            default:
                return 'bg-light text-dark';
        }
    }

    public function ticket()
    {
        return $this->hasOne(Ticket::class);
    }
}
