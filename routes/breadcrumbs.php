<?php

Breadcrumbs::for('index', function($trail){
	$trail->push('Home', route('index'));
});

// Audio pages

Breadcrumbs::for('audio-index', function($trail){
	$trail->parent('index');
	$trail->push('Audio Library', route('audio-index'));
});

Breadcrumbs::for('audio-search', function($trail){
	$trail->parent('audio-index');
	$trail->push('Search', route('audio-search'));
});

Breadcrumbs::for('audio-upload', function($trail){
	$trail->parent('audio-index');
	$trail->push('Upload', route('audio-upload'));
});

Breadcrumbs::for('audio-view', function($trail, $audio){
	$trail->parent('audio-index');
	$trail->push($audio->title, route('audio-view', $audio->id));
});

// Playlist pages

Breadcrumbs::for('playlist-index', function($trail){
	$trail->parent('audio-index');
	$trail->push('Playlists', route('playlist-index'));
});

Breadcrumbs::for('playlist-view', function($trail, $playlist){
	$trail->parent('playlist-index');
	$trail->push($playlist->name, route('playlist-view', $playlist->id));
});

Breadcrumbs::for('playlist-create', function($trail){
	$trail->parent('playlist-index');
	$trail->push('Create', route('playlist-create'));
});

Breadcrumbs::for('playlist-edit', function($trail, $playlist){
	$trail->parent('playlist-view', $playlist);
	$trail->push($playlist->name, route('playlist-edit', $playlist->id));
});

// Audiowall pages

Breadcrumbs::for('audiowall-index', function($trail){
	$trail->parent('index');
	$trail->push('Audiowalls', route('audiowall-index'));
});

Breadcrumbs::for('audiowall-view', function($trail, $set){
	$trail->parent('audiowall-index');
	$trail->push($set->name, route('audiowall-view', $set->id));
});

Breadcrumbs::for('audiowall-settings', function($trail, $set){
	$trail->parent('audiowall-view', $set);
	$trail->push('Settings', route('audiowall-settings', $set->id));
});

Breadcrumbs::for('audiowall-delete', function($trail, $set){
	$trail->parent('audiowall-view', $set);
	$trail->push('Delete', route('audiowall-settings', $set->id));
});

// Showplan pages

Breadcrumbs::for('showplan-index', function($trail){
	$trail->parent('index');
	$trail->push('Showplans', route('showplan-index'));
});

Breadcrumbs::for('showplan-edit', function($trail, $showplan){
	$trail->parent('showplan-index');
	$trail->push($showplan->name, route('showplan-edit', $showplan->id));
});

Breadcrumbs::for('showplan-delete', function($trail, $showplan){
	$trail->parent('showplan-edit', $showplan);
	$trail->push('Delete', route('showplan-delete', $showplan->id));
});

Breadcrumbs::for('showplan-settings', function($trail, $showplan){
	$trail->parent('showplan-edit', $showplan);
	$trail->push('Settings', route('showplan-settings', $showplan->id));
});


// Admin pages

Breadcrumbs::for('admin-index', function($trail){
	$trail->parent('index');
	$trail->push('Admin', route('admin-index'));
});

// Admin Group Pages

Breadcrumbs::for('admin-group-index', function($trail){
	$trail->parent('admin-index');
	$trail->push('Groups', route('admin-group-index'));
});

Breadcrumbs::for('admin-group-members', function($trail, $group_id){
	$trail->parent('admin-group-index');
	$trail->push('Membership', route('admin-group-members', $group_id));
});

Breadcrumbs::for('admin-group-permission', function($trail, $group_id){
	$trail->parent('admin-group-index');
	$trail->push('Permissions', route('admin-group-permission', $group_id));
});

// Admin Studio Logins

Breadcrumbs::for('admin-studio-index', function($trail){
	$trail->parent('admin-index');
	$trail->push('Studio Logins');
});

// Admin Sustainer

Breadcrumbs::for('admin-sustainer-index', function($trail){
	$trail->parent('admin-index');
	$trail->push('Sustainer');
});

// Admin LDAP

Breadcrumbs::for('admin-ldap', function($trail){
	$trail->parent('admin-index');
	$trail->push('User Search');
});