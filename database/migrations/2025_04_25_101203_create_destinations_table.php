<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/..._create_destinations_table.php
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique(); // Untuk URL yang ramah SEO
            $table->text('description');
            $table->string('location')->nullable(); // Tambahkan lokasi jika perlu
            $table->string('operating_hours')->nullable();
            $table->decimal('base_ticket_price', 10, 2);
            $table->integer('visitor_quota')->nullable(); // Kuota per hari/sesi? Perlu diperjelas
            $table->string('main_photo')->nullable(); // Path foto utama
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
