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
        Schema::create('kalkulator_fuzzies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('index_fuzzy_id')->references('id')->on('index_fuzzies');
            $table->string('nama_bayi');
            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan']);
            $table->double('berat_badan', 8, 2);
            $table->integer('usia');
            $table->integer('tinggi_badan');
            $table->double('z_score_bbu', 8, 2)->nullable();
            $table->double('z_score_tbu', 8, 2)->nullable();
            $table->double('z_score_bbtb', 8, 2)->nullable();
            $table->json('fuzzy_bbu')->nullable();
            $table->json('fuzzy_tbu')->nullable();
            $table->json('fuzzy_bbtb')->nullable();
            $table->json('kondisi_anak')->nullable();
            $table->longText('kesimpulan')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kalkulator_fuzzies');
    }
};
