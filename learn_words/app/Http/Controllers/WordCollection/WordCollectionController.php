<?php

namespace App\Http\Controllers\WordCollection;

use App\Http\Controllers\Controller;
use App\Http\Requests\WordCollection\StoreCollectionRequest;
use App\Http\Controllers\WordCollection\Services\CreateCollectionService;
use App\Http\Controllers\WordCollection\Services\EditCollectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\WordCollection\AddWordToCollectionsRequest;
use App\Http\Controllers\WordCollection\Services\AddWordToCollectionsService;
use App\Models\Collection;

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

    public function edit(Request $request)
    {
        // Servicio para editar y consultar colecciones/palabras
        $service = new EditCollectionService();

        // 1. Buscar colecciones (paginación y búsqueda)
        if ($request->has('page') || $request->has('query')) {
            $result = $service->searchCollections(
                $request->input('query', ''),
                $request->input('page', 1)
            );
            return response()->json($result);
        }

        // 2. Mostrar detalle de colección y palabras
        if ($request->has('collection_id') && !$request->has('remove_word_id') && !$request->has('get_word') && !$request->has('update_word')) {
            $result = $service->getCollectionDetail($request->input('collection_id'));
            return response()->json($result);
        }

        // 3. Quitar palabra de la colección
        if ($request->has('remove_word_id') && $request->has('collection_id')) {
            $success = $service->removeWordFromCollection($request->input('collection_id'), $request->input('remove_word_id'));
            return response()->json(['success' => $success]);
        }

        // 4. Obtener datos de una palabra
        if ($request->has('get_word') && $request->has('word_id')) {
            $word = $service->getWord($request->input('word_id'));
            return response()->json(['word' => $word]);
        }

        // 5. Editar palabra
        if ($request->has('update_word') && $request->has('word_id')) {
            $success = $service->updateWord(
                $request->input('word_id'),
                $request->input('word'),
                $request->input('translation'),
                $request->input('image_url')
            );
            return response()->json(['success' => $success]);
        }

        // Default: error
        return response()->json(['error' => 'Invalid request'], 400);
    }

    public function add(Request $request)
    {
        $request->validate(['collection_id' => 'required|exists:collections,id']);
        $collection = Collection::findOrFail($request->input('collection_id'));
        $words = $collection->words()->get();

        return view('collections.add', ['collection' => $collection,'words' => $words,]);
    }
    public function addWord(AddWordToCollectionsRequest $request)
    {
        $word = (new AddWordToCollectionsService())->add($request);

        return response()->json(['success' => true, 'word' => $word]);
    }
}