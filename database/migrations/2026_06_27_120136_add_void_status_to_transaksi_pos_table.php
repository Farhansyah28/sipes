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
        Schema::table('transaksi_pos', function (Blueprint $table) {
            $table->enum('status', ['Sukses', 'Batal'])->default('Sukses')->after('metode_pembayaran');
            $table->text('alasan_batal')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_pos', function (Blueprint $table) {
            $table->dropColumn(['status', 'alasan_batal']);
        });
    }
};
