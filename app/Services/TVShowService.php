<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class TVShowService
{
    protected $baseUrl = 'http://api.tvmaze.com';
    protected $cacheDuration = 3600;

    public function searchShows($query)
    {
        try {
            $response = Http::get("{$this->baseUrl}/search/shows", ['q' => $query]);

            if ($response->failed()) {
                throw new RequestException($response);
            }

            return $response->json();
        } catch (RequestException $e) {
            throw new \Exception('Failed to fetch data from TVMaze', 0, $e);
        }
    }

    public function getFilteredShows($shows, $query)
    {
        return collect($shows)->filter(function ($show) use ($query) {
            return strcasecmp($show['show']['name'], $query) === 0;
        })->values();
    }
}
