<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Coba drop foreign key (cek nama constraint jika default tidak bisa)
             try {
                 $table->dropForeign(['payment_id']);
             } catch (\Exception $e) {
                 echo "Warning: Could not drop foreign key 'bookings_payment_id_foreign'. May need manual check.\n";
             }
             // Opsional: hapus kolomnya juga jika tidak dipakai lagi
             // $table->dropColumn('payment_id');
         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
