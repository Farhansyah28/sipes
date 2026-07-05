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
        Schema::table('gedungs', function (Blueprint $table) {
            $table->enum('jenis_kelamin', ['L', 'P', 'Campur'])->default('Campur')->after('nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gedungs', function (Blueprint $table) {
            //
        });
    }
};
