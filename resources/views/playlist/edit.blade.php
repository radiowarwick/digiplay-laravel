@extends('layouts.app')

@section('title', 'Edit Playlist')

@section('breadcrumbs')
	{{ Breadcrumbs::render('playlist-edit', $playlist) }}
@endsection

@section('content')
	<h1>Edit Playlist - {{ $playlist->name }}</h1>

	<form method="POST">
		{{ csrf_field() }}

		<div class="form-group">
			<label for="name">Name</label>
			<input type="text" name="name" id="name" class="form-control" value="{{ $playlist->name }}">
		</div>
		<div class="form-check">
			<input type="checkbox" class="form-check-input" id="sustainer" name="sustainer" value="t">
			<label class="form-check-label" for="sustainer" {{ $playlist->sustainer == 't' ? 'checked' : '' }}>Sustainer playlist?</label>
		</div>
		<div class="form-group">
			<label for="colour">Playlist colour</label>
			<input type="color" name="colour" id="colour" value="#{{ $playlist->colour != null ? $playlist->colour->colour : 'ffffff' }}">
		</div>
		<div class="form-group">
			<button class="btn btn-warning">Save</button>
			<a href="{{ route('playlist-view', $playlist->id) }}" class="btn btn-danger">Cancel</a>
		</div>
	</form>

	@if($errors->any())
		@foreach ($errors->all() as $error)
			<p class="text-warning">{{ $error }}</p>
		@endforeach
	@endif
@endsection