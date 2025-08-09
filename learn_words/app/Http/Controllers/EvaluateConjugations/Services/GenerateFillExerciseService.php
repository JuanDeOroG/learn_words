<?php

namespace App\Http\Controllers\EvaluateConjugations\Services;

use App\Models\Word;
use App\Models\Conjugation;
use Illuminate\Support\Arr;

use function Laravel\Prompts\form;

class GenerateFillExerciseService
{
    public function generateFill($columns, $toFill, $conjugationSelected = [])
    {
        // obtener un grupo de palabras
        if (empty($conjugationSelected)) {
            // if no conjugations are selected, get a random word group
            $wordGroup = Word::inRandomOrder()->first();
        } else {
            // if conjugations are selected, get a random word group with those conjugations
            $wordGroup = Word::whereIn('conjugation_id', $conjugationSelected)->inRandomOrder()->first();
            // dd($wordGroup);
        }

        if (!$wordGroup) {
            return [
                'columns' => [],
                'wordForms' => [],
                'toFill' => [],
                'groupKey' => null,
            ];
        }

        $groupKey = $wordGroup->group_key;

        // traer todas las formas de ese verbo
        $forms = Word::where('group_key', $groupKey)
            ->with('conjugation')
            ->get();

        // dd($forms);
        //  objener todas las conjugaciones de esa palabra registradas
        $allConjugations = array_keys($forms->pluck('word', 'conjugation.name')->toArray());
        // dd($allConjugations);

        // seleccionar las conjugaciones (columnas) que se mostrarán, de forma aleatoria, limitado por el número de columnas solicitado por el usuario
        $columns = Arr::random($allConjugations, min($columns, count($allConjugations)));

        // definir qué campos serán para llenar
        if (!empty($conjugationSelected)) {
            $toFill = $forms->whereIn('conjugation.id', $conjugationSelected)->pluck('conjugation.name')->toArray();
        } else {
            // si el usuario no seleccionó conjugaciones específicas, seleccionar aleatoriamente los campos a llenar
            $toFill = Arr::random($columns, min($toFill, count($columns)));
        }
        // dd($toFill); 
        return [
            'columns' => $columns,
            'wordForms' => $forms->pluck('word', 'conjugation.name')->toArray(),
            'toFill' => $toFill,
            'groupKey' => $groupKey,
        ];
    }
}
