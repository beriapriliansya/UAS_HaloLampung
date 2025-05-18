<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Destination;
use App\Models\News; 

class HomeController extends Controller
{
        public function index()
    {
        $destinations = Destination::latest()->take(6)->get(); // Ambil 6 destinasi terbaru
        $latestNews = News::published() // Ambil berita yg sudah publish
                            ->latest('published_at') // Urutkan dari terbaru
                            ->take(3) // Ambil 3 berita terbaru
                            ->get();

        return view('home', compact('destinations', 'latestNews'));
    }

    public function show(Destination $destination)
    {
        // Eager load fasilitas yang tersedia (gunakan relasi 'facilities' yg sudah difilter di model)
        $destination->load(['facilities']); // Pastikan relasi 'facilities' di model Destination sudah benar

        // Kirim data ke view detail
        return view('destinations.show', compact('destination'));
    }
}
