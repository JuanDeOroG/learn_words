<?php

namespace App\Http\Requests\EvaluateFill;

use Illuminate\Foundation\Http\FormRequest;

class SetFillConfigurationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'word_source' => 'required|in:random,collections',
            'evaluation_mode' => 'required|in:imageAndText,image,audio,example,all',
            'direction' => 'required|in:en_to_es,es_to_en',
            'words_count' => 'required|integer|min:1|max:100',
            'collections' => 'nullable|array',
            'collections.*' => 'integer|exists:collections,id',
        ];
    }
}