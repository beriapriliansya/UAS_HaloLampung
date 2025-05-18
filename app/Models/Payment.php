<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // Status Constants (gunakan Enum jika PHP 8.1+)
    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILURE = 'failure';
    const STATUS_EXPIRED = 'expired';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_PARTIALLY_REFUNDED = 'partially_refunded';

    const REFUND_NONE = 'none';
    const REFUND_REQUESTED = 'requested';
    const REFUND_PROCESSING = 'processing';
    const REFUND_COMPLETED = 'completed';
    const REFUND_FAILED = 'failed';


    protected $fillable = [
        'booking_id',
        'transaction_id',
        'payment_gateway',
        'amount',
        'status',
        'gateway_details',
        'paid_at',
        'expired_at',
        'refund_status',
        'refunded_amount',
        'refunded_at',
        'refund_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_details' => 'json', // Penting untuk casting ke array/object
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
        'refunded_amount' => 'decimal:2',
        'refunded_at' => 'datetime',
    ];

    // Relasi ke Booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // --- Accessors (Contoh) ---

    public function getStatusLabelAttribute(): string
    {
         switch ($this->status) {
            case self::STATUS_PENDING: return 'Pending';
            case self::STATUS_SUCCESS: return 'Sukses';
            case self::STATUS_FAILURE: return 'Gagal';
            case self::STATUS_EXPIRED: return 'Kadaluarsa';
            case self::STATUS_REFUNDED: return 'Direfund Penuh';
            case self::STATUS_PARTIALLY_REFUNDED: return 'Direfund Sebagian';
            default: return ucfirst($this->status);
        }
    }

    public function getStatusBadgeClassAttribute(): string
    {
         switch ($this->status) {
            case self::STATUS_PENDING: return 'bg-warning text-dark';
            case self::STATUS_SUCCESS: return 'bg-success';
            case self::STATUS_FAILURE: return 'bg-danger';
            case self::STATUS_EXPIRED: return 'bg-secondary';
            case self::STATUS_REFUNDED: return 'bg-info text-dark';
            case self::STATUS_PARTIALLY_REFUNDED: return 'bg-primary';
            default: return 'bg-light text-dark';
        }
    }

     public function getRefundStatusLabelAttribute(): string
    {
         switch ($this->refund_status) {
            case self::REFUND_NONE: return 'Tidak Ada';
            case self::REFUND_REQUESTED: return 'Diminta';
            case self::REFUND_PROCESSING: return 'Diproses';
            case self::REFUND_COMPLETED: return 'Selesai';
            case self::REFUND_FAILED: return 'Gagal';
            default: return ucfirst($this->refund_status ?? 'N/A');
        }
    }

     public function getRefundStatusBadgeClassAttribute(): string
    {
         switch ($this->refund_status) {
            case self::REFUND_NONE: return 'bg-light text-dark';
            case self::REFUND_REQUESTED: return 'bg-warning text-dark';
            case self::REFUND_PROCESSING: return 'bg-primary';
            case self::REFUND_COMPLETED: return 'bg-success';
            case self::REFUND_FAILED: return 'bg-danger';
            default: return 'bg-secondary';
        }
    }

    // Helper untuk cek apakah bisa di-refund
    public function canBeRefunded(): bool
    {
        // Hanya bisa refund jika status sukses dan belum ada proses refund aktif/selesai
        return $this->status === self::STATUS_SUCCESS &&
               in_array($this->refund_status, [self::REFUND_NONE, self::REFUND_FAILED, null]);
    }
}