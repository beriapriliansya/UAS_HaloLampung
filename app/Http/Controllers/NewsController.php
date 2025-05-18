<?php

namespace App\Http\Controllers;

use App\Models\News; // Import model News
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Menampilkan daftar semua berita yang sudah dipublikasi.
     */
    public function index(Request $request)
    {
        $newsItems = News::published() // Gunakan scope 'published'
                            ->with('author:id,name') // Eager load author (hanya id dan nama)
                            ->latest('published_at') // Urutkan dari yang terbaru dipublikasi
                            ->paginate(9); // Misal 9 berita per halaman

        return view('news.index', compact('newsItems'));
    }

    /**
     * Menampilkan detail satu berita berdasarkan slug.
     */
    public function show(News $newsItem) // Route model binding berdasarkan slug
    {
        // Pastikan berita yang diminta sudah dipublikasi
        // atau user adalah admin (untuk preview draft, opsional)
        if (!$newsItem->is_published || ($newsItem->published_at && $newsItem->published_at->isFuture())) {
             // Jika tidak ingin admin preview, hapus kondisi auth admin
            // if (!auth()->check() || !auth()->user()->is_admin) {
                abort(404);
            // }
        }

        // Eager load author
        $newsItem->load('author:id,name');

        // Ambil juga beberapa berita terbaru lainnya (kecuali yang sedang dilihat) untuk sidebar/rekomendasi
        $recentNews = News::published()
                            ->where('id', '!=', $newsItem->id)
                            ->latest('published_at')
                            ->take(5)
                            ->get();

        return view('news.show', compact('newsItem', 'recentNews'));
    }
}