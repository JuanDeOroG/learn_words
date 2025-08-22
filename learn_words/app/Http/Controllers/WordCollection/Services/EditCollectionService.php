<?php

namespace App\Http\Controllers\WordCollection\Services;

use App\Models\Collection;
use App\Models\Word;

class EditCollectionService
{
    // Buscar colecciones con paginación y búsqueda
    public function searchCollections($query = '', $page = 1, $perPage = 11)
    {
        $collections = Collection::query()
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                  ->orWhere('description', 'like', "%$query%");
            })
            ->orderBy('name')
            ->skip(($page - 1) * $perPage)
            ->take($perPage + 1)
            ->get();

        $hasMore = $collections->count() > $perPage;
        $collections = $collections->take($perPage);

        return [
            'collections' => $collections,
            'hasMore' => $hasMore
        ];
    }

    // Obtener detalle de colección y palabras
    public function getCollectionDetail($collectionId)
    {
        $collection = Collection::find($collectionId);
        $words = $collection ? $collection->words()->get() : [];
        return [
            'collection' => $collection,
            'words' => $words
        ];
    }

    // Quitar palabra de la colección
    public function removeWordFromCollection($collectionId, $wordId)
    {
        $collection = Collection::find($collectionId);
        if ($collection) {
            $collection->words()->detach($wordId);
            return true;
        }
        return false;
    }

    // Obtener datos de una palabra
    public function getWord($wordId)
    {
        return Word::find($wordId);
    }

    // Editar palabra
    public function updateWord($wordId, $word, $translation)
    {
        $w = Word::find($wordId);
        if ($w) {
            $w->word = $word;
            // $w->translation = $translation;
            $w->save();
            return true;
        }
        return false;
    }
}