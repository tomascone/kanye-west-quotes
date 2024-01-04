<?php

use App\Http\Controllers\ApiFavouritesQuotesController;
use App\Http\Controllers\ApiProfileController;
use App\Http\Controllers\ApiQuoteController;
use App\Http\Controllers\ApiUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    // User
    Route::get('/users', [ApiUserController::class, 'index']);
    Route::delete('/users/{id}', [ApiUserController::class, 'destroy']);

    // Profile
    Route::get('/profile/{id}', [ApiProfileController::class, 'show']);
    // Route::put('/profile', [ApiProfileController::class, 'update']);
    Route::put('/profile/{id}', [ApiProfileController::class, 'update']);

    // Quotes
    Route::get('/quotes/{num?}', [ApiQuoteController::class, 'index'])
        ->middleware('throttle:quotesApi');
    Route::post('/quotes/add_to_fav', [ApiQuoteController::class, 'store']);

    // Favourites
    Route::get('/favourites', [ApiFavouritesQuotesController::class, 'index']);
    Route::delete('/favourites/{id}', [ApiFavouritesQuotesController::class, 'destroy']);
});
