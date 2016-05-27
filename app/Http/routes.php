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

use App\Device;
use App\Firmware;

Route::get('/', function () {
    return view('welcome')
        ->with('firmwares', Firmware::all())
        ->with('devices', Device::all());
});

Route::auth();

Route::get('/dashboard', 'DashboardController@index');

// devices
Route::get('/devices', 'DevicesController@getList');

Route::get('/devices/add', 'DevicesController@getAdd');
Route::post('/devices/add', 'DevicesController@postAdd');

Route::get('/devices/edit/{id}', 'DevicesController@getEdit');
Route::post('/devices/edit/{id}', 'DevicesController@postEdit');

Route::get('/devices/delete/{id}', 'DevicesController@getDelete');

// categories
Route::get('/categories', 'CategoriesController@getList');

Route::get('/categories/add', 'CategoriesController@getAdd');
Route::post('/categories/add', 'CategoriesController@postAdd');

Route::get('/categories/edit/{id}', 'CategoriesController@getEdit');
Route::post('/categories/edit/{id}', 'CategoriesController@postEdit');

Route::get('/categories/delete/{id}', 'CategoriesController@getDelete');