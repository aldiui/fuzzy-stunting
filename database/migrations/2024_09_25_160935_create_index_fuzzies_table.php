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
        Schema::create('index_fuzzies', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->double('range_awal', 8, 2);
            $table->double('range_akhir', 8, 2);
            $table->double('range_awal_fuzzy', 8, 2);
            $table->double('range_akhir_fuzzy', 8, 2);
            $table->double('himpunan_fuzzy_awal', 8, 2);
            $table->double('himpunan_fuzzy_akhir', 8, 2);
            $table->enum('tipe', ['Trapesium', 'Segitiga']);
            $table->text('interval');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('index_fuzzies');
    }
};
