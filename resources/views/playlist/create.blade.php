@extends('layouts.app')

@section('title', 'Create Playlist')

@section('breadcrumbs')
	{{ Breadcrumbs::render('playlist-create') }}
@endsection

@section('content')
	<h1>Create Playlist</h1>

	<form method="POST">
		{{ csrf_field() }}

		<div class="form-group">
			<label for="name">Name</label>
			<input type="text" name="name" id="name" class="form-control">
		</div>
		<div class="form-check">
			<input type="checkbox" class="form-check-input" id="sustainer" name="sustainer" value="t">
			<label class="form-check-label" for="sustainer">Sustainer playlist?</label>
		</div>
		<div class="form-group">
			<label for="colour">Playlist colour</label>
			<input type="color" name="colour" id="colour" value="#ffffff">
		</div>
		<div class="form-group">
			<button class="btn btn-warning">Create</button>
		</div>
	</form>

	@if($errors->any())
		@foreach ($errors->all() as $error)
			<p class="text-warning">{{ $error }}</p>
		@endforeach
	@endif
@endsection