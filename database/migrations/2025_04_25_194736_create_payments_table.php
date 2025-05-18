<?php

// database/migrations/..._create_payments_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            // Relasi utama: Satu payment terikat ke satu booking
            $table->foreignId('booking_id')->unique()->constrained()->onDelete('cascade');
            $table->string('transaction_id')->nullable()->unique()->index(); // ID dari payment gateway
            $table->string('payment_gateway')->nullable(); // e.g., 'midtrans', 'xendit', 'manual_transfer'
            $table->decimal('amount', 12, 2); // Jumlah yang dibayarkan/seharusnya
            $table->string('status')->default('pending')->index(); // pending, success, failure, expired, refunded, partially_refunded
            $table->json('gateway_details')->nullable(); // Raw response atau detail penting dari gateway
            $table->timestamp('paid_at')->nullable(); // Waktu pembayaran sukses dikonfirmasi
            $table->timestamp('expired_at')->nullable(); // Waktu kadaluarsa pembayaran (jika ada)
            $table->string('refund_status')->nullable()->default('none')->index(); // none, requested, processing, completed, failed
            $table->decimal('refunded_amount', 12, 2)->nullable(); // Jumlah yang di-refund
            $table->timestamp('refunded_at')->nullable(); // Waktu refund selesai
            $table->text('refund_reason')->nullable(); // Alasan refund
            $table->timestamps();
        });
    }

    public function down(): void
    {
         // Drop foreign key constraint dari bookings dulu jika ada
        if (Schema::hasColumn('bookings', 'payment_id')) {
            Schema::table('bookings', function (Blueprint $table) {
                 // Dapatkan nama constraint foreign key (biasanya nama_tabel_nama_kolom_foreign)
                 // Anda mungkin perlu mengecek nama constraint spesifik di database Anda
                try {
                     $table->dropForeign(['payment_id']); // Coba drop standar
                } catch (\Exception $e) {
                     // Cari nama constraint jika standar gagal (sesuaikan jika perlu)
                     // $foreignKeys = Schema::getConnection()->getDoctrineSchemaManager()->listTableForeignKeys('bookings');
                     // foreach ($foreignKeys as $foreignKey) {
                     //     if (in_array('payment_id', $foreignKey->getLocalColumns())) {
                     //         $table->dropForeign($foreignKey->getName());
                     //         break;
                     //     }
                     // }
                     // Atau hapus kolom saja jika constraint sulit ditemukan
                     // $table->dropColumn('payment_id');
                     echo "Warning: Could not automatically drop foreign key for payment_id on bookings table. Please check manually.\n";
                }

            });
        }
        Schema::dropIfExists('payments');
    }
};