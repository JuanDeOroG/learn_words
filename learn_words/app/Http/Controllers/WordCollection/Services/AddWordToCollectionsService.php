<?php

namespace App\Http\Controllers\WordCollection\Services;

use App\Models\Word;
use App\Models\Collection;

class AddWordToCollectionsService
{
    public function add($request)
    {   
        $validated = $request->validated();
        // dd($validated);

        $word = Word::create([
            'word' => $validated['word'],
            'translation' => $validated['translation'] ?? null,
            'image_url' => $validated['image_url'] ?? null,
        ]);
        $collection = Collection::find($validated['collection_id']);
        if ($collection) {
            $collection->words()->attach($word->id);
        }
        return $word;
    }
}