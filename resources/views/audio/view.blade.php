@extends('layouts.app')

@section('title', 'Track View - ' . $audio->title)

@section('breadcrumbs')
	{{ Breadcrumbs::render('audio-view', $audio) }}
@endsection

@section('content')
	<h1>Track Editing</h1>

	<div class="row">
		<div class="col-sm-8">
			<div class="form-group">
				<label for="title">Title</label>
				<input type="text" id="title" class="form-control" value="{{ $audio->title }}" {{ (!$can_edit) ? 'disabled' : '' }}>
			</div>
			<div class="form-group">
				<label for="artist">Artist</label>
				<input type="text" id="artist" class="form-control" value="{{ $audio->artist->name }}" {{ (!$can_edit) ? 'disabled' : '' }}>
			</div>
			<div class="form-group">
				<label for="album">Album</label>
				<input type="text" id="album" class="form-control" value="{{ $audio->album->name }}" {{ (!$can_edit) ? 'disabled' : '' }}>
			</div>
			<div class="form-group">
				<label for="type">Audio Type</label>
				<select class="form-control" id="type">
					<option value="1">Music</option>
					<option value="2">Jingle</option>
					<option value="3">Advert</option>
					<option value="4">Prerec</option>
				</select>
			</div>
			@if($can_edit)
				<div class="form-group">
					<button type="submit" class="btn btn-success">Update</button>
					<button type="submit" class="btn btn-danger">Delete</button>
				</div>
			@endif
		</div>
		<div class="col-sm-4">
			<div class="row">
				<div class="col-sm">Origin</div>
				<div class="col-sm">{{ $audio->origin }}</div>
			</div>
			<div class="row">
				<div class="col-sm">Length</div>
				<div class="col-sm">{{ $audio->lengthString() }}</div>
			</div>
			<div class="row">
				<div class="col-sm">Upload</div>
				<div class="col-sm">{{ date("d/m/Y H:i", $audio->import_date) }}</div>
			</div>
		</div>
	</div>
@endsection