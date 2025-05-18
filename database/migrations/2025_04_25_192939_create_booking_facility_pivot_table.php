<?php

// database/migrations/..._create_booking_facility_pivot_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_facility', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade'); // Jika booking dihapus, pivot ikut hilang
            $table->foreignId('facility_id')->constrained()->onDelete('restrict'); // Jangan hapus facility jika masih ada di booking
            $table->integer('quantity')->default(1); // Jumlah fasilitas yg dipesan
            $table->decimal('price_at_booking', 10, 2); // Harga per unit fasilitas saat itu
            $table->timestamps(); // Opsional

            $table->unique(['booking_id', 'facility_id']); // Hanya bisa pesan 1 jenis fasilitas per booking
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_facility');
    }
};