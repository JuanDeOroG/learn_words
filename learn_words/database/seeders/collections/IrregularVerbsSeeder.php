<?php

namespace Database\Seeders\collections;

use App\Models\Collection;
use App\Models\User;
use App\Models\Word;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IrregularVerbsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $collection = Collection::create([
            'name' => 'Irregular Verbs',
            'description' => 'Collection of irregular verbs.',
        ]);

        // Traer desde el registro 1 hasta el 279 de la tabla words
        $irregularVerbs = Word::whereBetween('id', [1, 279])->get();

        foreach ($irregularVerbs as $verb) {
            $collection->words()->attach($verb->id);
        }
    }
}
