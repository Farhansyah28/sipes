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
        Schema::create('transaksi_pos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->foreignId('santri_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('kasir_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal_transaksi');
            $table->integer('total_harga');
            $table->enum('metode_pembayaran', ['Tunai', 'Tabungan']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_pos');
    }
};
