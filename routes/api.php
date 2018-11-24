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

Route::middleware('auth:api')->get('/user/:id', function (Request $request) {
    //  return $request
    return $request->user();
});

Route::post('/login', 'AuthController@login');

Route::post('/register', 'AuthController@register');

Route::group(['middleware' => ['jwt.auth']], function () {
    /* here add the jwt protected routes */
    Route::get('/test', function () {
        return response()->json(['foo'=>'bar']);
    });
    Route::post('/toggle-on', [
        'uses'=>'MeterController@toggleOn'
    ]);

    Route::post('/toggle-off', [
        'uses'=>'MeterController@toggleOff'
    ]);

    Route::post('/power-time', [
        'uses'=>'MeterController@rechargeMeter'
    ]);

    Route::post('/energy-budget', [
        'uses'=>'MeterController@setEnergyBudget'
    ]);

    Route::post('/iot-data', [
        'uses'=>'MeterController@sendIOTData'
    ]);

    # blog routes API missing documentation

    Route::post('/post', [
        'uses'=>'BlogController@create'
    ]);

    Route::delete('/post/:id', [
        'uses'=>'BlogController@delete'
    ]);

    Route::put('/post/:id', [
        'uses'=>'BlogController@update'
    ]);
});

Route::post('/collector', [
    'uses'=>'CollectorController@collect'
]);

Route::post('/fota', [
    'uses'=>'FOTAController@saveBIN'
]);

Route::get('/fota', [
    'uses'=>'FOTAController@getBIN'
]);
