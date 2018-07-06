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

Route::get('/', function(){
	return view('index');
})->middleware('auth')->name('index');

Route::group(['middleware' => ['web']], function(){ 
	Route::get('/login', 'AuthController@getLogin')->name('login');
	Route::post('/login', 'AuthController@postLogin');
	Route::get('/logout', 'AuthController@getLogout')->name('logout');
});

Route::get('/users', function(){
	$users = App\User::all();
	return view('users', ['users' => $users]);
});

Route::group(['middleware' => ['auth']], function(){
	// Audio Searching
	Route::get('/audio', 'AudioController@getIndex')->name('audio-index');
	Route::get('/audio/search', 'AudioController@getSearch')->name('audio-search');

});