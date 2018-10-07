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

Route::middleware('auth:api')->get('/user/:id', function (Request $request){
  //  return $request
  return $request->user();
});

Route::post('/login','AuthController@login');

Route::group(['middleware' => ['jwt.auth']], function() {
    /* here add the jwt protected routes */
    Route::get('test', function(){
        return response()->json(['foo'=>'bar']);
    });
});

Route::post('/collector', [
    'uses'=>'CollectorController@collect'
]);
