<?php

// database/migrations/..._create_tickets_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            // Relasi ke Booking (Satu booking = satu tiket utama dalam skema ini)
            $table->foreignId('booking_id')->unique()->constrained()->onDelete('cascade');
            $table->uuid('ticket_code')->unique(); // Kode unik tiket (UUID lebih baik dari string random pendek)
            $table->string('status')->default('valid')->index(); // valid, used, expired, cancelled
            $table->timestamp('checked_in_at')->nullable(); // Waktu check-in/scan
            $table->foreignId('checked_in_by')->nullable()->constrained('users')->onDelete('set null'); // Admin/Staff yg melakukan check-in (opsional)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};