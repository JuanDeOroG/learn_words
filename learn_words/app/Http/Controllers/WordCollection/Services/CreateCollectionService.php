<?php

namespace App\Http\Controllers\WordCollection\Services;

use App\Models\Collection;

class CreateCollectionService
{
    public function create($request)
    {
        return Collection::create([
            'name' => $request->input('name'),
            'description' => $request->input('description') ?? null,
        ]);
    }
}