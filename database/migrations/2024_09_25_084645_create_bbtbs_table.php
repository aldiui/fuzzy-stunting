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
        Schema::create('bbtbs', function (Blueprint $table) {
            $table->id();
            $table->double('tinggi_badan', 8, 2);
            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan']);
            $table->boolean('status_usia_dibawah_24_bulan');
            $table->double('standar_deviasi_minus_3', 8, 2);
            $table->double('standar_deviasi_minus_2', 8, 2);
            $table->double('standar_deviasi_minus_1', 8, 2);
            $table->double('standar_deviasi_median', 8, 2);
            $table->double('standar_deviasi_plus_1', 8, 2);
            $table->double('standar_deviasi_plus_2', 8, 2);
            $table->double('standar_deviasi_plus_3', 8, 2);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bbtbs');
    }
};