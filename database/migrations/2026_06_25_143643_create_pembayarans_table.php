<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('tagihan_id')->constrained('tagihans')->cascadeOnDelete();
            $table->decimal('nominal_dibayar', 15, 2);
            $table->date('tanggal_bayar');
            $table->string('metode_pembayaran')->default('Tunai'); // Tunai, Transfer, Potong Tabungan
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
