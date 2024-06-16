<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;

class TVShowService
{
    protected $baseUrl = 'http://api.tvmaze.com';
    protected $cacheDuration = 3600;

    public function searchShows(string $query): array
    {
        $cacheKey = 'tv_shows_' . md5($query);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::get("{$this->baseUrl}/search/shows", ['q' => $query]);

            if ($response->failed()) {
                throw new RequestException($response);
            }

            $data = $response->json();

            Cache::put($cacheKey, $data, $this->cacheDuration);

            return $data;
        } catch (RequestException $e) {
            throw new \Exception('Failed to fetch data from TVMaze: ' . $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            throw new \Exception('An unexpected error occurred: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getFilteredShows(array $shows, string $query): Collection
    {
        return collect($shows)->filter(function ($show) use ($query) {
            return strcasecmp($show['show']['name'], $query) === 0;
        })->values();
    }
}
