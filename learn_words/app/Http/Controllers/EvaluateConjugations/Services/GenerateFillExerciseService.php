<?php

namespace App\Http\Controllers\EvaluateConjugations\Services;

use App\Models\Word;
use App\Models\Conjugation;
use Illuminate\Support\Arr;

class GenerateFillExerciseService
{
    public function generateFill($columns, $toFill, $wordsCount)
    {
        // 1. Selecciona un grupo aleatorio de palabras (un verbo)
        $group = Word::inRandomOrder()->first();
        if (!$group) {
            return [
                'columns' => [],
                'wordForms' => [],
                'toFill' => [],
                'groupKey' => null,
            ];
        }

        $groupKey = $group->group_key;

        // 2. Obtiene todas las formas de ese verbo
        $forms = Word::where('group_key', $groupKey)
            ->with('conjugation')
            ->get()
            ->pluck('word', 'conjugation.name')
            ->toArray();

        // dd($forms);

        // 3. Obtiene sólo las conjugaciones presentes en el grupo seleccionado
        $allConjugations = array_keys($forms);

        // 4. Selecciona columnas a mostrar (aleatorio o todas si hay menos)
        $columns = Arr::random($allConjugations, min($columns, count($allConjugations)));

        // 5. Selecciona cuáles campos serán para llenar (aleatorio)
        $toFill = Arr::random($columns, min($toFill, count($columns)));

        // 6. Prepara el array para la vista
        return [
            'columns' => $columns,
            'wordForms' => $forms,
            'toFill' => $toFill,
            'groupKey' => $groupKey,
        ];
    }
}