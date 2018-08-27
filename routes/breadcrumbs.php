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