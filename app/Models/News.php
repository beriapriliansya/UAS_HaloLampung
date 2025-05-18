<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Untuk slug
use Illuminate\Support\Facades\Auth; // Untuk set author otomatis

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'user_id',
        'published_at',
        'is_published',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    // Otomatis buat slug dan set user_id saat menyimpan
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($news) {
            // Buat slug
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title);
                // Pastikan slug unik
                $count = static::where('slug', $news->slug)->count();
                if ($count > 0) {
                    $news->slug = $news->slug . '-' . ($count + 1); // Atau gunakan UUID/timestamp
                }
            }
            // Set user_id jika belum ada dan user login
            if (empty($news->user_id) && Auth::check()) {
                $news->user_id = Auth::id();
            }
            // Jika is_published dan published_at kosong, set published_at ke now()
            if ($news->is_published && empty($news->published_at)) {
                $news->published_at = now();
            }
        });

        static::updating(function ($news) {
            // Update slug jika title berubah
            if ($news->isDirty('title') && empty($news->slug)) { // Atau jika ingin slug selalu update: if ($news->isDirty('title')) {
                $news->slug = Str::slug($news->title);
                $count = static::where('slug', $news->slug)->where('id', '!=', $news->id)->count();
                if ($count > 0) {
                    $news->slug = $news->slug . '-' . ($count + 1);
                }
            }
            // Jika is_published diubah ke true dan published_at kosong, set published_at
            if ($news->isDirty('is_published') && $news->is_published && empty($news->published_at)) {
                $news->published_at = now();
            }
            // Jika is_published diubah ke false, set published_at ke null (atau biarkan, tergantung logika)
            // if ($news->isDirty('is_published') && !$news->is_published) {
            //     $news->published_at = null;
            // }
        });
    }

    // Relasi ke User (Author)
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Accessor untuk mendapatkan URL gambar utama
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }
        // Return placeholder jika tidak ada gambar
        return asset('images/placeholder-news.jpg'); // Buat gambar placeholder news
    }

    // Scope untuk mengambil hanya berita yang sudah dipublikasi dan published_at tidak di masa depan
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at') // Jika published_at null tapi is_published true (dianggap publish sekarang)
                    ->orWhere('published_at', '<=', now()); // Atau published_at sudah lewat/sekarang
            });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug'; // Gunakan 'slug' untuk route model binding
    }
}
