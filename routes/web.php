<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController as AdminAuthenticatedSessionController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NewsletterSubscriptionController; // <-- Import

// == Route Autentikasi Admin ==
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        // HANYA LOGIN
        Route::get('login', [AdminAuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AdminAuthenticatedSessionController::class, 'store']);
    });

    // LOGOUT (tetap perlu middleware auth)
    Route::post('logout', [AdminAuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth')
                ->name('logout');
});

// == Route Publik ==
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinations/{destination:slug}', [DestinationController::class, 'show'])->name('destinations.show');


Route::middleware(['auth', 'verified', 'public_user'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // == Route Booking User ==
    Route::get('/booking/{destination:slug}/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/{destination:slug}', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/lihat-booking-saya/{bookingCode}', [BookingController::class, 'show'])
        ->name('booking.show')
        ->where('bookingCode', '.*');
        
    // == Route Pembayaran ==
    Route::get('/payment/initiate/{booking}', [PaymentController::class, 'initiatePayment'])
        ->name('payment.initiate')
        ->where('booking', '.*');

    // == Route Riwayat Pemesanan User ==
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('my.bookings'); // << Tambahkan ini

    
    Route::get('/payment/finish', [PaymentController::class, 'handleRedirect'])->name('payment.finish');
    Route::get('/payment/unfinish', [PaymentController::class, 'handleRedirect'])->name('payment.unfinish');
    Route::get('/payment/error', [PaymentController::class, 'handleRedirect'])->name('payment.error');
});

// == Route Berita Publik ==
Route::get('/news', [NewsController::class, 'index'])->name('news.index'); // Daftar semua berita
Route::get('/news/{newsItem}', [NewsController::class, 'show'])->name('news.show'); // Detail berita (menggunakan slug karena getRouteKeyName() di model)
// == Akhir Route Berita Publik ==

// == Route Langganan Newsletter ==
Route::post('/subscribe-newsletter', [NewsletterSubscriptionController::class, 'store'])->name('newsletter.subscribe');
// Route untuk konfirmasi (jika pakai double opt-in)
Route::get('/subscribe/confirm/{token}', [NewsletterSubscriptionController::class, 'confirm'])->name('newsletter.confirm');
// Route untuk unsubscribe
Route::get('/unsubscribe/{token}', [NewsletterSubscriptionController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
// == Akhir Route Langganan Newsletter ==

require __DIR__ . '/auth.php';
