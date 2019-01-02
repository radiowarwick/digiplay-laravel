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

if (env('APP_ENV') == 'production') {
    URL::forceScheme('https');
}

if (! is_null(env('BASE_URL', null))) {
    URL::forceRootUrl(env('BASE_URL'));
}

Route::get('/', function () {
    return view('index');
})->middleware('auth')->name('index');

Route::get('/studio/{key}/login', 'StudioController@getLogin')->name('studio-login');
Route::post('/studio/{key}/login', 'StudioController@postLogin')->name('studio-login-post');

Route::group(['middleware' => ['web']], function () {
    Route::get('/login', 'AuthController@getLogin')->name('login');
    Route::post('/login', 'AuthController@postLogin')->name('login-post');
    Route::get('/logout', 'AuthController@getLogout')->name('logout');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', function () {
        return view('index');
    })->name('index');

    // Audio Searching
    Route::get('/audio', 'AudioController@getIndex')->name('audio-index');
    Route::get('/audio/search', 'AudioController@getSearch')->name('audio-search');
    Route::get('/audio/{id}', 'AudioController@getView')->where('id', '[0-9]+')->name('audio-view');

    Route::post('/audio/{id}', 'AudioController@postUpdateMetadata')->where('id', '[0-9]+');
    Route::post('/audio/{id}/delete', 'AudioController@postDelete')->where('id', '[0-9]+');
    Route::post('/audio/{id}/restore', 'AudioController@postRestore')->where('id', '[0-9]+');

    // Audio upload
    Route::get('/audio/upload', 'AudioUploadController@getUpload')->name('audio-upload');

    Route::post('/audio/upload', 'AudioUploadController@postUpload');
    Route::post('/audio/upload/delete', 'AudioUploadController@postDelete');
    Route::post('/audio/upload/import', 'AudioUploadController@postImport');

    // Audio Preview
    Route::get('/audio/preview/{id}.mp3', 'AudioController@getPreview')->where('id', '[0-9]+')->name('audio-preview');
    Route::get('/audio/download/{id}.flac', 'AudioController@getDownload')->where('id', '[0-9]+')->name('audio-download');

    // Playlists
    Route::get('/audio/playlist', 'PlaylistController@getIndex')->name('playlist-index');
    Route::get('/audio/playlist/{id}', 'PlaylistController@getView')->where('id', '[0-9]+')->name('playlist-view');
    Route::get('/audio/playlist/create', 'PlaylistController@getCreate')->name('playlist-create');
    Route::get('/audio/playlist/{id}/edit', 'PlaylistController@getEdit')->name('playlist-edit');

    Route::post('/audio/playlist/remove', 'PlaylistController@postRemove')->name('playlist-remove');
    Route::post('/audio/playlist/update', 'PlaylistController@postUpdate')->name('playlist-update');
    Route::post('/audio/playlist/create', 'PlaylistController@postCreate');
    Route::post('/audio/playlist/{id}/edit', 'PlaylistController@postEdit');

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
    Route::get('/studio/{key}/reset', 'StudioController@getReset');
    Route::get('/studio/{key}/loadplan/{id}', 'StudioController@getLoadShowplan')->where('id', '[0-9]+')->name('studio-load-plan');

    Route::post('/studio/{key}/log', 'StudioController@postLog');

    // Showplans
    Route::get('/showplan', 'ShowplanController@getIndex')->name('showplan-index');
    Route::get('/showplan/{id}', 'ShowplanController@getEdit')->name('showplan-edit')->where('id', '[0-9]+');
    Route::get('/showplan/{id}/swap/{first_id}/{second_id}', 'ShowplanController@getSwapItems')->where('id', '[0-9]+')->where('first_id', '[0-9]+')->where('second_id', '[0-9]+');
    Route::get('/showplan/{id}/remove/{item_id}', 'ShowplanController@getRemoveItem')->where('id', '[0-9]+')->where('item_id', '[0-9]+');
    Route::get('/showplan/{id}/delete', 'ShowplanController@getDelete')->name('showplan-delete')->where('id', '[0-9]+');
    Route::get('/showplan/{id}/delete/yes', 'ShowplanController@getDeleteYes')->name('showplan-delete-yes')->where('id', '[0-9]+');
    Route::get('/showplan/{id}/settings', 'ShowplanController@getSettings')->name('showplan-settings')->where('id', '[0-9]+');
    Route::get('/showplan/{id}/settings/remove/{username}', 'ShowplanController@getSettingRemove')->name('showplan-setting-remove')->where('id', '[0-9]+');
    Route::get('/showplan/{id}/add/{audio_id}', 'ShowplanController@getAddItem')->where('id', '[0-9]+')->where('audio_id', '[0-9]+');

    Route::post('/showplan/create', 'ShowplanController@postCreate')->name('showplan-create');
    Route::post('/showplan/{id}/settings/name', 'ShowplanController@postSettingName')->name('showplan-setting-name');
    Route::post('/showplan/{id}/settings/add', 'ShowplanController@postSettingAdd')->name('showplan-setting-add');

    // API/AJAX call
    Route::post('/ajax/search', 'Api\SearchController@postSearch');
    Route::post('/ajax/detail', 'Api\SearchController@postDetail');
    Route::post('/ajax/playlist', 'Api\SearchController@postPlaylist');
});

Route::group(['middleware' => ['permission:Can view admin page']], function () {
    Route::get('/admin', 'Admin\AdminController@getIndex')->name('admin-index');

    Route::group(['middleware' => ['permission:Can edit groups']], function () {
        Route::get('/admin/groups/', 'Admin\GroupController@getIndex')->name('admin-group-index');
        Route::get('/admin/groups/{id}/permission', 'Admin\GroupController@getPermission')->name('admin-group-permission')->where('id', '[0-9]+');
        Route::get('/admin/groups/{id}/members', 'Admin\GroupController@getMembers')->name('admin-group-members')->where('id', '[0-9]+');

        Route::post('/admin/groups/create', 'Admin\GroupController@postCreate')->name('admin-group-create');
        Route::post('/admin/groups/{id}/permission', 'Admin\GroupController@postPermission')->name('admin-group-permission-post')->where('id', '[0-9]+');
        Route::post('/admin/groups/{id}/members/add', 'Admin\GroupController@postAddMember')->name('admin-group-member-add-post')->where('id', '[0-9]+');
        Route::get('/admin/groups/{id}/members/remove/{username}', 'Admin\GroupController@getRemoveMember')->name('admin-group-member-remove')->where('id', '[0-9]+');
    });

    Route::group(['middleware' => ['permission:Can view studio logins']], function () {
        Route::get('/admin/studio/', 'Admin\StudioLoginController@getIndex')->name('admin-studio-index');
        Route::post('/admin/studio/', 'Admin\StudioLoginController@postIndex')->name('admin-studio-index');
    });

    Route::group(['middleware' => ['permission:Sustainer admin']], function () {
        Route::get('/admin/sustainer', 'Admin\SustainerAdminController@getIndex')->name('admin-sustainer-index');
        Route::post('/admin/sustainer', 'Admin\SustainerAdminController@postSaveSlot');
    });

    Route::group(['middleware' => ['permission:Can edit user LDAP']], function () {
        Route::get('/admin/ldap/', 'Admin\UserController@getUser')->name('admin-ldap-view');
        Route::post('/admin/ldap/', 'Admin\UserController@postUpdate')->name('admin-ldap-update');
    });
});
