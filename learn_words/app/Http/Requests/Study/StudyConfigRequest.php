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
        return [
            'collections'      => 'required|array|min:1',
            'collections.*'    => 'exists:collections,id',
            'order'            => 'nullable|in:least_studied,random,recent,older',
            'presentation_mode'=> 'nullable|in:flashcard,table,audio',
            'goal_type'        => 'nullable|in:time,quantity',
            'words_time'       => 'nullable|integer|min:1',
            'words_count'      => 'nullable|integer|min:1|max:100',
        ];
    }
}