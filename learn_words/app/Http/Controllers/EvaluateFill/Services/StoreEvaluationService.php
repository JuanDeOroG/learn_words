<?php

namespace App\Http\Controllers\EvaluateFill\Services;

use App\Models\Evaluation;

class StoreEvaluationService
{
    public function store($data)
    {
        return Evaluation::create($data);
    }
}