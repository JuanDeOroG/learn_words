<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;

    protected $fillable = [
        'word',
        'translation',
        'conjugation_id',
        'group_key',
        'image_url',
    ];

    /**
     * Relación con la conjugación (forma gramatical).
     */
    public function conjugation()
    {
        return $this->belongsTo(Conjugation::class);
    }

    /**
     * Relación muchos a muchos con Collection a través de la tabla word_collections.
     */
    public function collections()
    {
        return $this->belongsToMany(Collection::class, 'word_collections', 'word_id', 'collection_id')
            ->withTimestamps();
    }

    /**
     * Filtra palabras por colecciones seleccionadas.
     * @param array $collectionIds
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function filterByCollections(array $collectionIds)
    {
        $query = self::query();
        if (!empty($collectionIds)) {
            $query->whereHas('collections', function ($q) use ($collectionIds) {
                $q->whereIn('collections.id', $collectionIds);
            });
        }
        return $query;
    }

    /**
     * Incrementa el contador de estudio de la palabra en +1.
     */
    public function incrementStudyCount()
    {
        $this->study_count = ($this->study_count ?? 0) + 1;
        return $this->save();
    }
}
