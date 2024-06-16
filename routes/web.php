<?php

use App\Http\Controllers\TVShowController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::get('/', [TVShowController::class, 'search']);
});
