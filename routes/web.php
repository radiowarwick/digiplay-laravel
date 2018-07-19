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

if(!is_null(env('BASE_URL', null)))
	URL::forceRootUrl(env('BASE_URL'));

Route::group(['middleware' => ['web']], function(){ 
	Route::get('/login', 'AuthController@getLogin')->name('login');
	Route::post('/login', 'AuthController@postLogin')->name('login-post');
	Route::get('/logout', 'AuthController@getLogout')->name('logout');
});

Route::get('/users', function(){
	$users = App\User::all();
	return view('users', ['users' => $users]);
});

Route::group(['middleware' => ['auth']], function(){
	Route::get('/', function(){
		return view('index');
	})->name('index');

	// Audio Searching
	Route::get('/audio', 'AudioController@getIndex')->name('audio-index');
	Route::get('/audio/search', 'AudioController@getSearch')->name('audio-search');

	// Audiowalls
	Route::get('/audiowall', 'AudiowallController@getIndex')->name('audiowall-index');
	Route::get('/audiowall/{id}', 'AudiowallController@getView')->name('audiowall-view');
	Route::get('/audiowall/{id}/activate', 'AudiowallController@getActivate')->name('audiowall-activate');
	Route::get('/audiowall/{id}/settings', 'AudiowallController@getSettings')->name('audiowall-settings');
	Route::get('/audiowall/{id}/settings/remove/{username}', 'AudiowallController@getSettingsRemove')->name('audiowall-setting-remove');

	Route::post('/audiowall/{id}/settings/name', 'AudiowallController@postSettingsName')->name('audiowall-setting-name');
	Route::post('/audiowall/{id}/settings/add', 'AudiowallController@postSettingsAdd')->name('audiowall-setting-add');
	Route::post('/audiowall/{id}/settings/update/{username}', 'AudiowallController@postSettingsUpdate')->name('audiowall-setting-update');
});

Route::group(['middleware' => ['permission']], function(){
	Route::get('/admin', 'Admin\AdminController@getIndex')->name('admin-index');

	Route::group(['middleware' => ['permission:Can edit groups']], function(){
		Route::get('/admin/groups/', 'Admin\GroupController@getIndex')->name('admin-group-index');
		Route::get('/admin/groups/{id}/permission', 'Admin\GroupController@getPermission')->name('admin-group-permission');
		Route::get('/admin/groups/{id}/members', 'Admin\GroupController@getMembers')->name('admin-group-members');

		Route::post('/admin/groups/create', 'Admin\GroupController@postCreate')->name('admin-group-create');
		Route::post('/admin/groups/{id}/permission', 'Admin\GroupController@postPermission')->name('admin-group-permission-post');
		Route::post('/admin/groups/{id}/members/add', 'Admin\GroupController@postAddMember')->name('admin-group-member-add-post');
		Route::get('/admin/groups/{id}/members/remove/{username}', 'Admin\GroupController@getRemoveMember')->name('admin-group-member-remove');
	});
});