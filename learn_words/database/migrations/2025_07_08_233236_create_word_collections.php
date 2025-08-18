<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('word_collections', function (Blueprint $table) {
            $table->id();

            $table->integer('collection_id');
            $table->integer('word_id');

            $table->timestamps();

            // These field combinations must be unique
            $table->unique(['collection_id', 'word_id'], 'uq_collection_word');

            $table->foreign(['collection_id'], 'fk_collection_id')
                ->references('id')->on('collections')
                ->onUpdate('restrict')
                ->onDelete('cascade');

            $table->foreign(['word_id'], 'fk_word_id')
                ->references('id')->on('words')
                ->onUpdate('restrict')
                ->onDelete('cascade');

        });

        // Ejecutar un seeder de verbos irregulares
        Artisan::call('db:seed', [
            '--class' => "Database\\Seeders\\collections\\IrregularVerbsSeeder",
            '--force' => true, // Necesario en producción
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('word_collections', function (Blueprint $table) {
            $table->dropForeign('fk_collection_id');
            $table->dropForeign('fk_word_id');
        });
        Schema::dropIfExists('word_collections');
    }
};
