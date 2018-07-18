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
	$trail->push('Audiowall', route('audiowall-index'));
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