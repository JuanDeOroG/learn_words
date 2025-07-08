<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;

    protected $fillable = [
        'word',
        'conjugation_id',
        'group_key',
    ];

    /**
     * Relación con la conjugación (forma gramatical).
     */
    public function conjugation()
    {
        return $this->belongsTo(Conjugation::class);
    }
}
