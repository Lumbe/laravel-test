<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::get('properties',  ['uses' => 'MlsListingController@showAllListings']);

    Route::get('properties/{id}', ['uses' => 'MlsListingController@showOneListing']);

    Route::post('properties', ['uses' => 'MlsListingController@create']);

    Route::delete('properties/{id}', ['uses' => 'MlsListingController@delete']);

    Route::put('properties/{id}', ['uses' => 'MlsListingController@update']);
});
