<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    // relación muchos a muchos con Word a través de la tabla word_collections
    public function words()
    {
        return $this->belongsToMany(Word::class, 'word_collections', 'collection_id', 'word_id')
            ->withTimestamps();
    }
}
