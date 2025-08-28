<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class UnsplashService
{
    /**
     * Buscar imágenes en Unsplash según una consulta.
     */
    public function searchImage(Request $request)
    {
        $query = $request->input('query', '');
        $perPage = $request->input('perPage', 1);
        $images = $this->fetchImages($query, $perPage);

        return response()->json(['images' => $images]);
    }

    public function fetchImages($query, $perPage = 1)
    {
        $accessKey = config('services.unsplash.key');
        Log::info("Searching Unsplash for query: $query");
        $response = Http::withOptions(['verify' => false])->get('https://api.unsplash.com/search/photos', [
            'query' => $query,
            'per_page' => $perPage,
            'client_id' => $accessKey,
        ]);

        if ($response->ok() && isset($response['results'])) {
            // Devuelve un array de URLs pequeñas
            return collect($response['results'])->pluck('urls.small')->all();
        }

        // fallback si no hay imagen
        return ['https://via.placeholder.com/320x180?text=No+Image'];
    }
}