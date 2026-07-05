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
        Schema::create('import_sandboxes', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->string('model_type'); // misal: 'Santri'
            $table->json('row_data'); // Data mentah per baris
            $table->enum('status', ['Valid', 'Error']);
            $table->text('error_messages')->nullable(); // Pesan error jika ada
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_sandboxes');
    }
};
