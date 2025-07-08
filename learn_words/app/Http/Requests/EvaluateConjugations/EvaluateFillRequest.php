<?php
namespace App\Http\Requests\EvaluateConjugations;

use Illuminate\Foundation\Http\FormRequest;

class EvaluateFillRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'columns' => 'nullable|integer|min:2|max:8',
            'to_fill' => 'nullable|integer|min:1|max:8',
            'words_count' => 'nullable|integer|min:1|max:20',
        ];
    }
}