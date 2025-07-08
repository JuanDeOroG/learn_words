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
            1 => 'Base Form',
            2 => 'Past',
            3 => 'Past Participle',
            4 => 'Present Participle',
            5 => 'Future',
            6 => 'Present Perfect',
            7 => 'Past Perfect',
            8 => 'Future Perfect',
        ];

        foreach ($conjugations as $id => $name) {
            Conjugation::updateOrCreate(
                ['id' => $id],
                ['name' => $name]
            );
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
