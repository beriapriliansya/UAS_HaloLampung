<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DestinationController; // Import
use App\Http\Controllers\Admin\FacilityController; // Import
use App\Http\Controllers\Admin\DestinationFacilityController; // Import
use App\Http\Controllers\Admin\BookingController; // Import
use App\Http\Controllers\Admin\PaymentController; // Import
use App\Http\Controllers\Admin\TicketController; // Import
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\NewsController; // Import

// Import controller lain nanti di sini

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('destinations', DestinationController::class);
Route::resource('facilities', FacilityController::class)->except(['show']);

// Tambahkan route resource atau lainnya di bawah ini

// Mengelola fasilitas yang terhubung ke destinasi
Route::post('/destinations/{destination}/facilities', [DestinationFacilityController::class, 'attach'])
     ->name('destinations.facilities.attach');
Route::put('/destinations/{destination}/facilities/{facility}', [DestinationFacilityController::class, 'update'])
     ->name('destinations.facilities.update');
Route::delete('/destinations/{destination}/facilities/{facility}', [DestinationFacilityController::class, 'detach'])
     ->name('destinations.facilities.detach');

Route::prefix('bookings')->name('bookings.')->group(function () {
     Route::get('/', [BookingController::class, 'index'])->name('index');
     Route::get('/{booking}', [BookingController::class, 'show'])->name('show');
     // Contoh Aksi (bisa diubah ke POST jika lebih sesuai)
     Route::put('/{booking}/verify-payment', [BookingController::class, 'verifyPayment'])->name('verifyPayment');
     Route::put('/{booking}/cancel', [BookingController::class, 'cancel'])->name('cancel');
     Route::post('/{booking}/resend-ticket', [BookingController::class, 'resendTicket'])->name('resendTicket'); // Contoh kirim ulang tiket
     // Hati-hati dengan DELETE, mungkin lebih baik hanya cancel
     // Route::delete('/{booking}', [BookingController::class, 'destroy'])->name('destroy');
});

Route::prefix('payments')->name('payments.')->group(function () {
     Route::get('/', [PaymentController::class, 'index'])->name('index');
     Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
     // Aksi Refund
     Route::post('/{payment}/initiate-refund', [PaymentController::class, 'initiateRefund'])->name('initiateRefund');
     Route::put('/{payment}/update-refund', [PaymentController::class, 'updateRefundStatus'])->name('updateRefundStatus'); // Untuk update manual status refund
});

Route::prefix('tickets')->name('tickets.')->group(function () {
     Route::get('/', [TicketController::class, 'index'])->name('index');
     Route::get('/{ticket}', [TicketController::class, 'show'])->name('show');
     // Aksi Check-in (Manual dari detail tiket)
     Route::put('/{ticket}/check-in', [TicketController::class, 'checkIn'])->name('checkIn');
     // Halaman/Form untuk scan/input kode (Opsional)
     Route::get('/check', [TicketController::class, 'showCheckForm'])->name('checkForm');
     Route::post('/check', [TicketController::class, 'processCheck'])->name('processCheck');
});

Route::prefix('reports')->name('reports.')->group(function () {
     Route::get('/', [ReportController::class, 'index'])->name('index'); // Form generator
     Route::get('/generate', [ReportController::class, 'generate'])->name('generate'); // Proses & tampilkan/ekspor
});

// Manajemen Berita
Route::resource('news', NewsController::class);
