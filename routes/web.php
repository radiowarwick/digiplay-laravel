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
	return view('layouts.app');
})->middleware('auth');

Route::group(['middleware' => ['web']], function(){ 
	Route::get('/login', 'AuthController@getLogin');
	Route::post('/login', 'AuthController@postLogin');
	Route::get('/logout', 'AuthController@getLogout');
});

Route::get('/users', function(){
	$users = App\User::all();
	return view('users', ['users' => $users]);
});
