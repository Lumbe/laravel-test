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

    Route::get('locations',  ['uses' => 'LocationController@index']);

    // Route::get('locations/{id}', ['uses' => 'LocationController@show']);
    //
    // Route::post('locations', ['uses' => 'LocationController@create']);
    //
    // Route::delete('locations/{id}', ['uses' => 'LocationController@delete']);
    //
    // Route::put('locations/{id}', ['uses' => 'LocationController@update']);
});
