<?php

namespace App\Http\Controllers;

use App\Http\Requests\TVShowSearchRequest;
use App\Services\TVShowService;
use Illuminate\Http\Client\RequestException;

class TVShowController extends Controller
{
    public function __construct(protected TVShowService $service) {}

    public function search(TVShowSearchRequest $request)
    {
        $query = $request->validated('q');

        try {
            $shows = $this->service->searchShows($query);
            $filteredShows = $this->service->getFilteredShows($shows, $query);

            return response()->json($filteredShows);
        } catch (RequestException $e) {
            return response()->json(['error' => 'Failed to fetch data from TVMaze', 'message' => $e->getMessage()], $e->getCode());
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred', 'message' => $e->getMessage()], 500);
        }
    }
}
