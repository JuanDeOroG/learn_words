<?php

namespace App\Http\Requests\EvaluateFill;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvaluationRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules()
    {
        return [
            'type' => 'required|string',
            'config' => 'required|array',
            'score' => 'required|integer',
            'total' => 'required|integer',
            'correct' => 'nullable|array',
            'incorrect' => 'nullable|array',
            'started_at' => 'nullable|date',
            'finished_at' => 'nullable|date',
        ];
    }
}