@extends('layouts.app')

@section('title', 'Audiowall Settings')

@section('breadcrumbs')
	{{ Breadcrumbs::render('audiowall-index') }}
@endsection

@section('content')
	<h1>Audiowall Settings</h1>
	<h2>{{ $set->name }}</h2>
@endsection