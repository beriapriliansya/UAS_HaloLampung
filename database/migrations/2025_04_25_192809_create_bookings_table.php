<?php

// database/migrations/..._create_bookings_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique()->index(); // Kode unik pemesanan
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User yg pesan
            $table->foreignId('destination_id')->constrained()->onDelete('restrict'); // Destinasi yg dipesan
            $table->date('booking_date'); // Tanggal kunjungan
            $table->integer('num_tickets'); // Jumlah tiket utama
            $table->decimal('base_ticket_price_at_booking', 10, 2); // Harga tiket dasar saat pesan
            $table->decimal('total_facility_price', 10, 2)->default(0); // Total harga fasilitas
            $table->decimal('total_amount', 12, 2); // Total keseluruhan (tiket + fasilitas)
            $table->string('payment_method')->nullable(); // Misal: midtrans, manual_transfer
            $table->string('payment_status')->default('pending')->index(); // pending, success, failure, expired, cancelled
            $table->foreignId('payment_id')->nullable()->constrained()->onDelete('set null'); // Relasi ke tabel payments (opsional)
            $table->text('notes')->nullable(); // Catatan dari user
            $table->string('status')->default('pending_payment')->index(); // pending_payment, confirmed, cancelled, completed, failed
            $table->timestamps();

            $table->index(['created_at']); // Index untuk filter tanggal pesan
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};