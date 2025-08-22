<?php

namespace App\Http\Controllers\WordCollection;

use App\Http\Controllers\Controller;
use App\Http\Requests\WordCollection\StoreCollectionRequest;
use App\Http\Controllers\WordCollection\Services\CreateCollectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WordCollectionController extends Controller
{
    public function store(StoreCollectionRequest $request)
    {
        $service = new CreateCollectionService();
        $collection = $service->create($request);

        if ($request->ajax()) {
            // Log::info('Collection created via AJAX:', ['collection_id' => $collection->id]);
            return response()->json([
                'success' => true,
                'collection' => $collection
            ]);
        }

        return redirect()->route('wordCollection.index')->with('success', 'Collection created successfully.');
    }
}