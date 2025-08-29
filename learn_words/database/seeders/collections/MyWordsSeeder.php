<?php

namespace Database\Seeders\collections;

use Illuminate\Database\Seeder;
use App\Models\Word;
use App\Models\Collection;

class MyWordsSeeder extends Seeder
{
    public function run()
    {
        $jsonPath = database_path('seeders/collections/MyWords.json');
        $words = json_decode(file_get_contents($jsonPath), true);

        // Crear la colección si no existe
        $collection = Collection::firstOrCreate([
            'name' => 'AllMyWords'
        ], [
            'description' => 'Words from my personal vocabulary list.'
        ]);

        foreach ($words as $item) {
            $word = Word::create([
                'word' => $item['word'] ?? '',
                'translation' => $item['translation'] ?? '',
                // 'example' => $item['example'] ?? '',
                // 'ejemplo' => $item['ejemplo'] ?? '',
                // 'pronuntiation' => $item['pronuntiation'] ?? '',
            
            ]);
            // Asociar la palabra a la colección
            $collection->words()->attach($word->id);
        }
    }
}