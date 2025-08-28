<?php

namespace App\Http\Requests\Study;

use Illuminate\Foundation\Http\FormRequest;

class StudyConfigRequest extends FormRequest
{
    // permitir siempre la petición (puedes personalizar según tu lógica)
    public function authorize()
    {
        return true;
    }

    // reglas de validación para la configuración de estudio
    public function rules()
    {
        $rules = [
            'order'             => 'nullable|in:least_studied,random,recent,older',
            'presentation_mode' => 'nullable|in:flashcard,table,audio',
            'goal_type'         => 'nullable|in:time,quantity,no',
            'words_time'        => 'nullable|integer|min:1',
            'words_count'       => 'nullable|integer|min:1|max:100',
        ];

        // si existe chooseCollectionOption, añadir validación de collections
        if ($this->has('randomWordsOption') && $this->input('randomWordsOption') === 'no') {
            $rules['collections'] = 'required|array|min:1';
            $rules['collections.*'] = 'exists:collections,id';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'collections.required'      => 'You must select at least one collection.',
            'collections.array'         => 'Collections must be an array.',
            'collections.min'           => 'Select at least one collection.',
            'collections.*.exists'      => 'One or more selected collections do not exist.',
            'order.in'                  => 'Invalid study order selected.',
            'presentation_mode.in'      => 'Invalid presentation mode selected.',
            'goal_type.in'              => 'Invalid session goal type selected.',
            'words_time.integer'        => 'Time must be a valid number.',
            'words_time.min'            => 'Time must be at least 1 minute.',
            'words_count.integer'       => 'Number of words must be a valid number.',
            'words_count.min'           => 'Number of words must be at least 1.',
            'words_count.max'           => 'Number of words cannot be greater than 100.',
        ];
    }
}