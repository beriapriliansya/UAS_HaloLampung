<?php

// database/migrations/..._create_news_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique(); // Untuk URL SEO-friendly
            $table->text('excerpt')->nullable(); // Ringkasan/kutipan singkat
            $table->longText('content'); // Konten berita (bisa HTML dari editor WYSIWYG)
            $table->string('featured_image')->nullable(); // Path gambar utama berita
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Author (Admin yg membuat)
            $table->timestamp('published_at')->nullable()->index(); // Tanggal publikasi (bisa dijadwalkan)
            $table->boolean('is_published')->default(false)->index(); // Status publikasi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};