<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Destination; // Import model Destination

class DestinationController extends Controller
{
    // Menampilkan daftar semua destinasi
    public function index()
    {
         // Ambil destinasi terbaru dengan paginasi
        $destinations = Destination::latest()->paginate(12); // Misal 12 per halaman

        // Kirim data ke view index destinasi
        return view('destinations.index', compact('destinations'));
    }

    // Method show() akan kita isi nanti
    public function show(Destination $destination)
    {
        // Logic untuk menampilkan detail akan ditambahkan di langkah berikutnya
        return view('destinations.show', compact('destination'));
    }
}