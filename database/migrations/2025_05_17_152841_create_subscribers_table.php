<?php

// database/migrations/..._create_subscribers_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique(); // Email harus unik
            $table->string('token')->nullable()->unique(); // Untuk konfirmasi/unsubscribe (opsional)
            $table->timestamp('verified_at')->nullable(); // Jika menggunakan double opt-in
            $table->boolean('is_subscribed')->default(true); // Status langganan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};