@extends('layouts.app')

@section('title', 'Audiowalls')

@section('breadcrumbs')
	{{ Breadcrumbs::render('audiowall-delete', $set) }}
@endsection

@section('content')
	<h1>Delete Audiowall?</h1>

	<p>
		Are you sure you wish to delete the audiowall "{{ $set->name }}"?
	</p>

	<p>
		<a class="btn btn-lg btn-danger" href="{{ route('audiowall-delete-yes', $set->id) }}">Yes</a>
		<a class="btn btn-lg btn-success" href="{{ route('audiowall-index') }}">No</a>
	</p>
@endsection