<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('{path}', function () {
    return view('index');
})->where('path', '(.*)');


/**
 * Authenticate route
 */
Route::group([
    'namespace' => 'AdminAPI'
], function() {

    Route::post('authenticate', 'UsersController@authenticate');
    Route::post('logout', 'UsersController@logout');
});
