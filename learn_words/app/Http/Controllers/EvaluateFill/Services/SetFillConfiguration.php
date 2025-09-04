<?php

namespace App\Http\Controllers\EvaluateFill\Services;

use App\Models\Word;
use App\Models\Collection;

class SetFillConfiguration
{
    public function getWords($validated)
    {
        if ($validated['word_source'] === 'random') {
            return Word::inRandomOrder()->limit($validated['words_count'])->get();
        } elseif ($validated['word_source'] === 'collections' && !empty($validated['collections'])) {
            return Word::whereHas('collections', function($q) use ($validated) {
                $q->whereIn('collections.id', $validated['collections']);
            })->inRandomOrder()->limit($validated['words_count'])->get();
        }
        return collect();
    }
}