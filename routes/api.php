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

Route::group(['prefix' => 'country','middleware' => 'auth:api'], function () {
    Route::post('create','CountryController@create');
    Route::get('find','CountryController@findSimilar');
});

Route::group(['prefix' => 'city','middleware' => 'auth:api'], function () {
    Route::post('create','CityController@create');
    Route::get('find','CityController@findSimilar');
});

Route::group(['prefix' => 'node','middleware' => 'auth:api'], function () {
    Route::post('create','NodeController@create');
    Route::post('search','NodeController@search');
});
