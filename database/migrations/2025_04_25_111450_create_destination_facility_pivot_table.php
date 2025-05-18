<?php

// database/migrations/..._create_destination_facility_pivot_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destination_facility', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained()->onDelete('cascade'); // Foreign key ke destinations
            $table->foreignId('facility_id')->constrained()->onDelete('cascade');  // Foreign key ke facilities
            $table->decimal('price', 10, 2)->default(0); // Harga fasilitas di destinasi ini
            $table->integer('quota')->nullable(); // Kuota khusus fasilitas ini di destinasi ini (null jika tak terbatas)
            $table->boolean('is_available')->default(true); // Status ketersediaan di destinasi ini
            $table->timestamps(); // Opsional, jika perlu melacak kapan relasi dibuat/diupdate

            // Pastikan kombinasi destination_id dan facility_id unik
            $table->unique(['destination_id', 'facility_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destination_facility');
    }
};