<?php

namespace App\Providers;

// Import Model dan Observer
use App\Models\Booking;
use App\Observers\BookingObserver;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    // ================================================
    // === TAMBAHKAN PROPERTY $observers DI SINI ===
    // ================================================
    /**
     * The model observers for your application.
     *
     * @var array
     */
    protected $observers = [
        Booking::class => [BookingObserver::class], // Daftarkan observer di sini
    ];
    // ================================================
    // ================================================

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // Kode boot lainnya (jika ada)
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false; // Biarkan false jika Anda mendaftarkan secara eksplisit
    }
}