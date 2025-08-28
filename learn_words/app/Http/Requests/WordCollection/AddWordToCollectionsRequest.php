<?php

namespace App\Http\Requests\WordCollection;

use Illuminate\Foundation\Http\FormRequest;

class AddWordToCollectionsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'word' => 'required|string|max:255',
            // 'translation' => 'nullable|string|max:255',
            'image_url' => 'nullable|url',
            'collection_id' => 'required|integer|exists:collections,id',
        ];
    }
}