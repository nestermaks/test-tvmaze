<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TVShowController extends Controller
{
    public function search(Request $request)
    {
        try {
            $query = $request->query('q');

            if (!$query) {
                return response()->json(['error' => 'Invalid request'], 400);
            }

            $response = Http::get('http://api.tvmaze.com/search/shows', [
                'q' => $query
            ]);

            if ($response->failed()) {
                return response()->json(['error' => 'Failed to fetch data from TVMaze'], 500);
            }

            $shows = collect($response->json())->filter(function ($show) use ($query) {
                return strcasecmp($show['show']['name'], $query) === 0;
            })->values();

            return response()->json($shows);
        } catch (RequestException $e) {
            return response()->json(['error' => 'Failed to fetch data from TVMaze', 'message' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred', 'message' => $e->getMessage()], 500);
        }
    }
}
