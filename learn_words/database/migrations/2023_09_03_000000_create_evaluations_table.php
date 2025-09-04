<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationsTable extends Migration
{
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('type', 32); // fill, conjugation, etc.
            $table->json('config')->nullable(); // configuración usada
            $table->integer('score')->nullable();
            $table->integer('total')->nullable();
            $table->json('correct')->nullable(); // array de palabras correctas
            $table->json('incorrect')->nullable(); // array de palabras incorrectas
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluations');
    }
}