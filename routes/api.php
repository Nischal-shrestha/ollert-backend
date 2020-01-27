<?php

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



/**
 * API : v1
 */
Route::group([
    'namespace' => 'Api',
    'prefix' => 'v1'
], function ($router) {

    Route::get('/test',function(Request $request){
        return response()->json(App\Models\User\User::find(1));
    });

    /**
     * Test Route
     */
    Route::middleware('auth:api')->get('me', function (Request $request) {
        return $request->user();
    });


    /**
     * Authentication Routes
     */
    Route::group([
        'namespace' => 'Auth',
        'middleware' => 'api',
        'prefix' => 'auth'
    ], function ($router) {
        Route::post('login', 'AuthController@login')->name('auth.login');
        Route::post('register', 'AuthController@register')->name('auth.register');
        Route::post('refresh', 'AuthController@refresh')->name('auth.refresh');
        Route::delete('logout', 'AuthController@logout')->name('auth.logout');
    });


    /**
     * Board routes
     */
    Route::group([
        'middleware' => 'auth:api',
        'prefix' => 'board'
    ], function ($router) {
        Route::get('/', 'BoardController@index')->name('board.index');
        Route::post('/', 'BoardController@store')->name('board.store');
        Route::get('/{board}', 'BoardController@show')->name('board.show');
        Route::match(array('PUT', 'PATCH'), "/{board}", array(
            'uses' => 'BoardController@update',
            'as' => 'board.update'
        ));
        Route::delete('/{board}','BoardController@delete')->name('board.delete');
    });

    /**
     * Column Routes
     */
    Route::group([
        'middleware' => 'auth:api',
        'prefix'    =>  'column'
    ],function($router){
        Route::get('/','ColumnController@index')->name('column.index');
        Route::post('/','ColumnController@store')->name('column.store');
        Route::get('/{column}','ColumnController@show')->name('column.show');
        Route::match(array('PUT', 'PATCH'), "/{column}", array(
            'uses' => 'ColumnController@update',
            'as' => 'column.update'
        ));
        Route::delete('/{column}','ColumnController@delete')->name('column.delete');
    });


});
