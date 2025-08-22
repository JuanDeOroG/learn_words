<?php

namespace App\Http\Requests\WordCollection;

use Illuminate\Foundation\Http\FormRequest;

class StoreCollectionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:collections,name',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The collection name is required.',
            'name.unique' => 'This collection name already exists.',
        ];
    }
}