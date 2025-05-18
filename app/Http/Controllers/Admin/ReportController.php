<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Facility;
use App\Models\Destination;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Untuk query kompleks
use Maatwebsite\Excel\Facades\Excel; // Facade Excel
use App\Exports\GeneralReportExport; // Import class Export
use Barryvdh\DomPDF\Facade\Pdf; // Facade PDF

class ReportController extends Controller
{
    // Menampilkan form generator laporan
    public function index()
    {
        $destinations = Destination::orderBy('name')->pluck('name', 'id');
        return view('admin.reports.index', compact('destinations'));
    }

    // Memproses form, mengambil data, dan menampilkan/ekspor
    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:visitors,revenue,facilities',
            'date_range_type' => 'required|in:daily,weekly,monthly,custom',
            'start_date' => 'required_if:date_range_type,custom|nullable|date',
            'end_date' => 'required_if:date_range_type,custom|nullable|date|after_or_equal:start_date',
            'specific_date' => 'required_if:date_range_type,daily|nullable|date',
            'specific_week' => 'required_if:date_range_type,weekly|nullable|string', // Format YYYY-Www
            'specific_month' => 'required_if:date_range_type,monthly|nullable|string', // Format YYYY-MM
            'destination_id' => 'nullable|exists:destinations,id',
            'format' => 'required|in:screen,pdf,excel',
        ]);

        // Tentukan Tanggal Mulai & Akhir berdasarkan Pilihan Range
        $dateRange = $this->determineDateRange($request);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];
        $reportTitle = '';
        $reportData = [];
        $viewName = ''; // Nama view Blade untuk tabel laporan

        // Query Data berdasarkan Jenis Laporan
        switch ($request->report_type) {
            case 'visitors':
                $reportTitle = "Laporan Jumlah Pengunjung";
                $viewName = 'admin.reports._table_visitors'; // View parsial untuk tabel
                $reportData = $this->getVisitorReportData($startDate, $endDate, $request->destination_id);
                break;

            case 'revenue':
                $reportTitle = "Laporan Total Pemasukan";
                 $viewName = 'admin.reports._table_revenue';
                 $reportData = $this->getRevenueReportData($startDate, $endDate, $request->destination_id);
                break;

            case 'facilities':
                $reportTitle = "Laporan Fasilitas Terpopuler";
                 $viewName = 'admin.reports._table_facilities';
                 $reportData = $this->getFacilityReportData($startDate, $endDate, $request->destination_id);
                break;
        }

        // Tambahkan informasi tambahan untuk view/ekspor
        $dataForView = [
            'reportTitle' => $reportTitle,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'destination' => $request->destination_id ? Destination::find($request->destination_id) : null,
            'reportData' => $reportData,
            'request' => $request->all() // Kirim parameter request asli
        ];

        // Handle output berdasarkan format
        if ($request->format === 'pdf') {
            // Load view khusus PDF (atau view tabel jika sudah cukup sederhana)
            // Pastikan view ini tidak mengandung elemen/script yg kompleks
            $pdf = Pdf::loadView('admin.reports.pdf_template', $dataForView)
                        ->setPaper('a4', 'landscape'); // Atur kertas & orientasi
            $fileName = 'laporan_' . $request->report_type . '_' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.pdf';
            return $pdf->download($fileName);

        } elseif ($request->format === 'excel') {
            // Gunakan view tabel yang sama (pastikan tabel HTML sederhana)
            $fileName = 'laporan_' . $request->report_type . '_' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.xlsx';
            // Pass nama view tabel dan data ke Export class
             return Excel::download(new GeneralReportExport($viewName, $dataForView), $fileName);

        } else { // 'screen'
            // Tampilkan hasil di halaman web
            $destinations = Destination::orderBy('name')->pluck('name', 'id'); // Perlu untuk form lagi
             return view('admin.reports.index', array_merge($dataForView, ['destinations' => $destinations]));
        }
    }

    // Helper: Menentukan Start & End Date
    protected function determineDateRange(Request $request): array
    {
        $today = Carbon::today();
        $startDate = null;
        $endDate = null;

        switch ($request->date_range_type) {
            case 'daily':
                $date = Carbon::parse($request->specific_date ?? $today);
                $startDate = $date->copy()->startOfDay();
                $endDate = $date->copy()->endOfDay();
                break;
            case 'weekly':
                // Input format YYYY-Www (e.g., 2023-W45)
                $weekParts = explode('-W', $request->specific_week ?? $today->format('Y-\WW'));
                $year = $weekParts[0];
                $week = $weekParts[1];
                $startDate = Carbon::now()->setISODate($year, $week)->startOfWeek();
                $endDate = $startDate->copy()->endOfWeek();
                break;
            case 'monthly':
                 // Input format YYYY-MM (e.g., 2023-11)
                $month = Carbon::parse($request->specific_month ?? $today->format('Y-m') . '-01');
                $startDate = $month->copy()->startOfMonth();
                $endDate = $month->copy()->endOfMonth();
                break;
            case 'custom':
                $startDate = Carbon::parse($request->start_date)->startOfDay();
                $endDate = Carbon::parse($request->end_date)->endOfDay();
                break;
        }
        return ['start' => $startDate, 'end' => $endDate];
    }

    // Helper: Query Data Pengunjung
    protected function getVisitorReportData(Carbon $startDate, Carbon $endDate, $destinationId = null)
    {
         $query = Booking::select(
                DB::raw('DATE(booking_date) as date'), // Kelompokkan per tanggal kunjungan
                'destination_id',
                DB::raw('SUM(num_tickets) as total_visitors')
            )
            ->whereIn('status', [Booking::STATUS_CONFIRMED, Booking::STATUS_COMPLETED]) // Hanya yg confirm/selesai
            //->where('payment_status', Booking::PAYMENT_SUCCESS) // Alternatif: filter by payment
            ->whereBetween('booking_date', [$startDate->toDateString(), $endDate->toDateString()]) // Filter tanggal KUNJUNGAN
            ->groupBy('date', 'destination_id')
            ->orderBy('date', 'asc')
            ->with('destination:id,name'); // Eager load nama destinasi

        if ($destinationId) {
            $query->where('destination_id', $destinationId);
        }

        return $query->get();
    }

     // Helper: Query Data Pemasukan
    protected function getRevenueReportData(Carbon $startDate, Carbon $endDate, $destinationId = null)
    {
        $query = Payment::select(
                DB::raw('DATE(paid_at) as date'), // Kelompokkan per tanggal bayar
                'bookings.destination_id', // Ambil dari join booking
                DB::raw('SUM(payments.amount) as total_revenue')
            )
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id') // Join ke booking untuk filter destinasi
            ->where('payments.status', Payment::STATUS_SUCCESS) // Hanya yg sukses
            ->whereBetween('payments.paid_at', [$startDate, $endDate]) // Filter tanggal BAYAR
            ->groupBy('date', 'bookings.destination_id')
            ->orderBy('date', 'asc');
            // ->with('booking.destination:id,name'); // Tidak bisa langsung with setelah join, perlu select manual atau cara lain

         if ($destinationId) {
            $query->where('bookings.destination_id', $destinationId);
            $query->addSelect(DB::raw('(SELECT name FROM destinations WHERE id = ' . $destinationId . ') as destination_name')); // Tambahkan nama destinasi
         } else {
             // Jika ingin nama destinasi untuk semua, perlu join lagi atau subquery
             // Biarkan tanpa nama destinasi untuk kasus 'semua destinasi' demi kesederhanaan
         }

         // Fetch data dan tambahkan nama destinasi jika tidak difilter (jika perlu)
         $results = $query->get();
         if (!$destinationId && $results->isNotEmpty()) {
              // Ambil semua ID destinasi yang relevan
              $destinationIds = $results->pluck('destination_id')->unique()->filter();
              if ($destinationIds->isNotEmpty()) {
                  $destinations = Destination::whereIn('id', $destinationIds)->pluck('name', 'id');
                  // Map nama ke hasil query
                  $results->each(function ($item) use ($destinations) {
                      $item->destination_name = $destinations[$item->destination_id] ?? 'N/A';
                  });
              }
         }


        return $results;
    }

    // Helper: Query Data Fasilitas
    protected function getFacilityReportData(Carbon $startDate, Carbon $endDate, $destinationId = null)
    {
         $query = DB::table('booking_facility')
             ->select(
                 'booking_facility.facility_id',
                 'facilities.name as facility_name',
                 DB::raw('SUM(booking_facility.quantity) as total_ordered')
             )
             ->join('facilities', 'booking_facility.facility_id', '=', 'facilities.id')
             ->join('bookings', 'booking_facility.booking_id', '=', 'bookings.id')
             ->whereIn('bookings.status', [Booking::STATUS_CONFIRMED, Booking::STATUS_COMPLETED]) // Hanya dari booking yg confirm/selesai
             //->where('bookings.payment_status', Booking::PAYMENT_SUCCESS) // Alternatif: filter by payment
             ->whereBetween('bookings.booking_date', [$startDate->toDateString(), $endDate->toDateString()]) // Berdasarkan tanggal KUNJUNGAN booking
             ->groupBy('booking_facility.facility_id', 'facilities.name')
             ->orderBy('total_ordered', 'desc');

        if ($destinationId) {
            $query->where('bookings.destination_id', $destinationId);
        }

        return $query->get();
    }
}