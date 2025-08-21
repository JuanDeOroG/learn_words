<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UnsplashService
{
    public function searchImage($query)
    {
        $accessKey = config('services.unsplash.key');
        Log::info("Searching Unsplash for query: $accessKey");
        $response = Http::withOptions(['verify' => false])->get('https://api.unsplash.com/search/photos', [
            'query' => $query,
            'per_page' => 1,
            'client_id' => $accessKey,
        ]);

        if ($response->ok() && isset($response['results'][0]['urls']['small'])) {
            return $response['results'][0]['urls']['small'];
        }

        // fallback si no hay imagen
        return 'https://via.placeholder.com/320x180?text=No+Image';
    }
}