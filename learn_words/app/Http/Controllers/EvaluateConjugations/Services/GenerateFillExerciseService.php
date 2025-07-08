<?php

namespace App\Http\Controllers\EvaluateConjugations\Services;

use App\Models\Word;
use App\Models\Conjugation;
use Illuminate\Support\Arr;

class GenerateFillExerciseService
{
    public function generateFill($columns, $toFill, $wordsCount)
    {
        // 1. Select a random group of words (a verb)
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

        // 2. Get all forms of that verb
        $forms = Word::where('group_key', $groupKey)
            ->with('conjugation')
            ->get()
            ->pluck('word', 'conjugation.name')
            ->toArray();

        // 3. Get only the conjugations present in the selected group
        $allConjugations = array_keys($forms);

        // 4. Select columns to display (random or all if fewer available)
        $columns = Arr::random($allConjugations, min($columns, count($allConjugations)));

        // 5. Select which fields will be for filling in (random)
        $toFill = Arr::random($columns, min($toFill, count($columns)));

        return [
            'columns' => $columns,
            'wordForms' => $forms,
            'toFill' => $toFill,
            'groupKey' => $groupKey,
        ];
    }
}
