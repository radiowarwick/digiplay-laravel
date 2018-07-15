<?php

Breadcrumbs::for('index', function($trail){
	$trail->push('Home', route('index'));
});

// Audio pages

Breadcrumbs::for('audio-index', function($trail){
	$trail->parent('index');
	$trail->push('Audio Library', route('audio-index'));
});

// Admin pages

Breadcrumbs::for('admin-index', function($trail){
	$trail->parent('index');
	$trail->push('Admin', route('admin-index'));
});

Breadcrumbs::for('admin-group-index', function($trail){
	$trail->parent('admin-index');
	$trail->push('Groups', route('admin-group-index'));
});