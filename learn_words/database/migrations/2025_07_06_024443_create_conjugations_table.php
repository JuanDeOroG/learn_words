<?php

use App\Models\Conjugation;
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
        Schema::create('conjugations', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 100)->unique('name');
        });

        $conjugations = [
            'Base Form',
            'Past',
            'Past Participle',
            'Present Participle',
            'Future',
            'Present Perfect',
            'Past Perfect',
            'Future Perfect',
        ];

        foreach ($conjugations as $name) {
            Conjugation::create(['name' => $name]);
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conjugations');
    }
};
