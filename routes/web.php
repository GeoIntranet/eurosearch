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

Route::get('/', function () {
    return redirect()->action('SearchController@index');
});

route::get('/search','SearchController@index')->name('getSearch');
route::post('/search','SearchController@search')->name('postSearch');
route::get('/search/delete','SearchController@deleteSearch')->name('deleteSearch');
route::get('/search/delete/{id}','SearchController@deleteOneSearch')->name('deleteOneSearch');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
