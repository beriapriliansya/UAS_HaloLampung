<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // Jika perlu set author manual di controller

class NewsController extends Controller
{
    public function index()
    {
        $newsItems = News::with('author')->latest()->paginate(10);
        return view('admin.news.index', compact('newsItems'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'published_at' => 'nullable|date',
            'is_published' => 'nullable|boolean',
        ]);

        // Handle is_published checkbox (jika tidak dicentang, tidak dikirim)
        $validated['is_published'] = $request->has('is_published');

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('news_images', 'public');
            $validated['featured_image'] = $path;
        }

        // user_id dan slug akan di-handle oleh model's boot method
        News::create($validated);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function show(News $news) // Parameter $news, bukan $newsItem
    {
        // Biasanya admin tidak perlu halaman show terpisah, langsung edit
        return redirect()->route('admin.news.edit', $news);
    }

    public function edit(News $news) // Parameter $news, bukan $newsItem
    {
        return view('admin.news.edit', ['newsItem' => $news]); // Kirim sebagai $newsItem ke view
    }

    public function update(Request $request, News $news) // Parameter $news
    {
         $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'published_at' => 'nullable|date',
            'is_published' => 'nullable|boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');

        if ($request->hasFile('featured_image')) {
            // Hapus gambar lama jika ada
            if ($news->featured_image && Storage::disk('public')->exists($news->featured_image)) {
                Storage::disk('public')->delete($news->featured_image);
            }
            $path = $request->file('featured_image')->store('news_images', 'public');
            $validated['featured_image'] = $path;
        } else {
            unset($validated['featured_image']); // Jangan update jika tidak ada file baru
        }

        $news->update($validated);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(News $news) // Parameter $news
    {
        // Hapus gambar terkait
        if ($news->featured_image && Storage::disk('public')->exists($news->featured_image)) {
            Storage::disk('public')->delete($news->featured_image);
        }
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dihapus.');
    }
}