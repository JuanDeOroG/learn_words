<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'type',
        'config',
        'score',
        'total',
        'correct',
        'incorrect',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'config' => 'array',
        'correct' => 'array',
        'incorrect' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];
}