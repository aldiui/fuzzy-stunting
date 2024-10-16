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
        Schema::create('rule_fuzzies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variabel_fuzzy_bbu_id')->references('id')->on('variabel_fuzzies');
            $table->foreignId('variabel_fuzzy_tbu_id')->references('id')->on('variabel_fuzzies');
            $table->foreignId('variabel_fuzzy_bbtb_id')->references('id')->on('variabel_fuzzies');
            $table->foreignId('index_fuzzy_id')->references('id')->on('index_fuzzies');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rule_fuzzies');
    }
};