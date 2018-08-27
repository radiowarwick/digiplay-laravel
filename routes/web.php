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

Route::get('/', function(){
	return view('index');
})->middleware('auth')->name('index');

Route::get('/studio/{key}/login', 'StudioController@getLogin')->name('studio-login');
Route::post('/studio/{key}/login', 'StudioController@postLogin')->name('studio-login-post');

Route::group(['middleware' => ['web']], function(){ 
	Route::get('/login', 'AuthController@getLogin')->name('login');
	Route::post('/login', 'AuthController@postLogin')->name('login-post');
	Route::get('/logout', 'AuthController@getLogout')->name('logout');
});

Route::group(['middleware' => ['auth']], function(){
	Route::get('/', function(){
		return view('index');
	})->name('index');

	// Audio Searching
	Route::get('/audio', 'AudioController@getIndex')->name('audio-index');
	Route::get('/audio/search', 'AudioController@getSearch')->name('audio-search');

	// Audio Preview
	Route::get('/audio/preview/{id}.mp3', 'AudioController@getPreview')->where('id', '[0-9]+')->name('audio-preview');

	// Audiowalls
	Route::get('/audiowall', 'AudiowallController@getIndex')->name('audiowall-index');
	Route::get('/audiowall/{id}', 'AudiowallController@getView')->name('audiowall-view')->where('id', '[0-9]+');
	Route::get('/audiowall/{id}/activate', 'AudiowallController@getActivate')->name('audiowall-activate')->where('id', '[0-9]+');
	Route::get('/audiowall/{id}/settings', 'AudiowallController@getSettings')->name('audiowall-settings')->where('id', '[0-9]+');
	Route::get('/audiowall/{id}/settings/remove/{username}', 'AudiowallController@getSettingsRemove')->name('audiowall-setting-remove')->where('id', '[0-9]+');
	Route::get('/audiowall/{id}/delete', 'AudiowallController@getDelete')->name('audiowall-delete-confirm')->where('id', '[0-9]+');
	Route::get('/audiowall/{id}/delete/yes', 'AudiowallController@getDeleteYes')->name('audiowall-delete-yes')->where('id', '[0-9]+');

	Route::post('/audiowall/{id}/settings/name', 'AudiowallController@postSettingsName')->name('audiowall-setting-name')->where('id', '[0-9]+');
	Route::post('/audiowall/{id}/settings/add', 'AudiowallController@postSettingsAdd')->name('audiowall-setting-add')->where('id', '[0-9]+');
	Route::post('/audiowall/{id}/settings/update/{username}', 'AudiowallController@postSettingsUpdate')->name('audiowall-setting-update')->where('id', '[0-9]+');
	Route::post('/audiowall/{id}/save', 'AudiowallController@postSaveAudiowall')->name('audiowall-save')->where('id', '[0-9]+');
	Route::post('/audiowall/create', 'AudiowallController@postCreateAudiowall')->name('audiowall-create');

	// Studio Interface
	Route::get('/studio/{key}', 'StudioController@getView')->name('studio-view');
	Route::get('/studio/{key}/logout', 'StudioController@getLogout')->name('studio-logout');
	Route::get('/studio/{key}/message/{id}', 'StudioController@getMessage')->where('id', '[0-9]+');
	Route::get('/studio/{key}/messages/{id}', 'StudioController@getLatestMessages')->where('id', '[0-9]+');
	Route::get('/studio/{key}/addplan/{id}', 'StudioController@getAddShowplan')->where('id', '[0-9]+');
	Route::get('/studio/{key}/removeplan/{id}', 'StudioController@getRemoveShowplan')->where('id', '[0-9]+');
	Route::get('/studio/{key}/selectitem/{id}', 'StudioController@getSelectShowplanItem')->where('id', '[0-9]+');

	Route::post('/studio/{key}/log', 'StudioController@postLog');

	// Showplans
	Route::get('/showplan', 'ShowplanController@getIndex')->name('showplan-index');
	Route::get('/showplan/{id}', 'ShowplanController@getEdit')->name('showplan-edit')->where('id', '[0-9]+');
	Route::get('/showplan/{id}/swap/{first_id}/{second_id}', 'ShowplanController@getSwapItems')->where('id', '[0-9]+')->where('first_id', '[0-9]+')->where('second_id', '[0-9]+');
	Route::get('/showplan/{id}/remove/{item_id}', 'ShowplanController@getRemoveItem')->where('id', '[0-9]+')->where('item_id', '[0-9]+');

	Route::post('/showplan/create', 'ShowplanController@postCreate')->name('showplan-create');

	// API/AJAX call
	Route::post('ajax/search', 'Api\SearchController@postSearch');
});

Route::group(['middleware' => ['permission']], function(){
	Route::get('/admin', 'Admin\AdminController@getIndex')->name('admin-index');

	Route::group(['middleware' => ['permission:Can edit groups']], function(){
		Route::get('/admin/groups/', 'Admin\GroupController@getIndex')->name('admin-group-index');
		Route::get('/admin/groups/{id}/permission', 'Admin\GroupController@getPermission')->name('admin-group-permission')->where('id', '[0-9]+');
		Route::get('/admin/groups/{id}/members', 'Admin\GroupController@getMembers')->name('admin-group-members')->where('id', '[0-9]+');

		Route::post('/admin/groups/create', 'Admin\GroupController@postCreate')->name('admin-group-create');
		Route::post('/admin/groups/{id}/permission', 'Admin\GroupController@postPermission')->name('admin-group-permission-post')->where('id', '[0-9]+');
		Route::post('/admin/groups/{id}/members/add', 'Admin\GroupController@postAddMember')->name('admin-group-member-add-post')->where('id', '[0-9]+');
		Route::get('/admin/groups/{id}/members/remove/{username}', 'Admin\GroupController@getRemoveMember')->name('admin-group-member-remove')->where('id', '[0-9]+');
	});

	Route::group(['middleware' => ['permission:Can view studio logins']], function(){
		Route::get('/admin/studio/', 'Admin\StudioLoginController@getIndex')->name('admin-studio-index');
		Route::post('/admin/studio/', 'Admin\StudioLoginController@postIndex')->name('admin-studio-index');
	});
});