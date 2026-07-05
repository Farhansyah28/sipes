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
        Schema::create('kas_pesantrens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->date('tanggal');
            $table->enum('tipe', ['Masuk', 'Keluar']);
            $table->string('kategori'); // e.g., 'SPP', 'Kantin POS', 'Restock Kantin', 'Operasional', 'Gaji'
            $table->decimal('nominal', 15, 2);
            $table->string('keterangan')->nullable();
            
            // Polymorphic relation to trace back the transaction
            $table->string('referensi_type')->nullable(); // e.g., 'App\Models\Pembayaran'
            $table->unsignedBigInteger('referensi_id')->nullable();
            
            $table->timestamps();
            
            $table->index(['referensi_type', 'referensi_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas_pesantrens');
    }
};
