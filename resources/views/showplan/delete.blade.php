@extends('layouts.app')

@section('title', 'Showplan Delete')

@section('breadcrumbs')
	{{ Breadcrumbs::render('showplan-delete', $showplan) }}
@endsection

@section('content')
	<h1>Delete Showplan?</h1>

	<p>
		Are you sure you wish to delete the showplan "{{ $showplan->name }}"?
	</p>

	<p>
		<a class="btn btn-lg btn-danger" href="{{ route('showplan-delete-yes', $showplan->id) }}">Yes</a>
		<a class="btn btn-lg btn-success" href="{{ route('showplan-index') }}">No</a>
	</p>
@endsection