<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
            $table->foreignId('kategori_tagihan_id')->constrained('kategori_tagihans')->cascadeOnDelete();
            $table->decimal('nominal', 15, 2);
            $table->decimal('sisa_tagihan', 15, 2);
            $table->enum('status', ['Belum Bayar', 'Sebagian', 'Lunas'])->default('Belum Bayar');
            $table->date('jatuh_tempo')->nullable();
            $table->string('bulan_tagihan')->nullable(); // Misal: 2024-07
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};
