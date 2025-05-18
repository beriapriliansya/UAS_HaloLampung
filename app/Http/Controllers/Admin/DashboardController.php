<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Destination; // Import model
use App\Models\Booking;     // Import model
use App\Models\Payment;    // Import model
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Contoh pengambilan data statistik
        $totalDestinations = Destination::count();
        $todayBookings = Booking::whereDate('created_at', Carbon::today())->count();
        $monthBookings = Booking::whereMonth('created_at', Carbon::now()->month)
                                ->whereYear('created_at', Carbon::now()->year)
                                ->count();

        // Asumsi status payment di tabel 'bookings' atau 'payments'
        // Contoh jika di tabel 'payments'
        $successfulPaymentsToday = Payment::where('status', 'success')
                                        ->whereDate('created_at', Carbon::today())
                                        ->count();
        $failedPaymentsToday = Payment::where('status', 'failure')
                                      ->whereDate('created_at', Carbon::today())
                                      ->count();

        // Contoh Top Destinasi (perlu relasi dan grouping)
         $topDestinations = Destination::withCount(['bookings' => function ($query) {
                                $query->whereHas('payment', fn($q) => $q->where('status', 'success')); // Hanya booking yang sudah dibayar
                            }])
                            ->orderBy('bookings_count', 'desc')
                            ->take(5)
                            ->get();

        // Contoh Notifikasi Transaksi Terbaru (misal 5 terakhir)
        $recentBookings = Booking::with('user', 'destination', 'payment') // Eager load relasi
                              ->latest()
                              ->take(5)
                              ->get();


        return view('admin.dashboard', compact(
            'totalDestinations',
            'todayBookings',
            'monthBookings',
            'successfulPaymentsToday',
            'failedPaymentsToday',
            'topDestinations',
            'recentBookings'
        ));
    }
}