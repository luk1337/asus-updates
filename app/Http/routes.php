<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::get('/dashboard', 'DashboardController@index');

Route::get('/devices/add', 'DevicesController@getAdd');
Route::post('/devices/add', 'DevicesController@postAdd');

Route::get('/devices/edit/{id}', 'DevicesController@getEdit');
Route::post('/devices/edit/{id}', 'DevicesController@postEdit');

Route::get('/devices/delete/{id}', 'DevicesController@getDelete');