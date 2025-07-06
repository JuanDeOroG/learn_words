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
        Schema::create('words', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('word', 100);
            $table->integer('conjugation_id')->index('fk_conjugation_id');
            $table->string('group_key', 50)->index('idx_group_key');
            $table->timestamps();


            $table->index(['word', 'conjugation_id'], 'idx_word_conjugation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('words');
    }
};
