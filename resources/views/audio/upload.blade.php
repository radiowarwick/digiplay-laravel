@extends('layouts.app')

@section('title', 'Audio Upload')

@section('breadcrumbs')
	{{ Breadcrumbs::render('audio-upload') }}
@endsection

@section('content')
	<h1>Audio Upload</h1>

	<div class="row">
		<div class="col-sm-2">
			<form class="form-inline" id="form-upload" enctype="multipart/form-data">
				{{ csrf_field() }}
				<label class="btn btn-primary md-2 mr-md-2">
					<i class="fa fa-upload"></i>
					File
					<input type="file" name="file" hidden>
				</label>
				<button type="submit" id="btn-upload" class="btn btn-warning md-2">Upload</button>
			</form>
		</div>
		<div class="col-sm-10">
			<div class="progress audio-progress">
				<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar">0%</div>
			</div>
		</div>
	</div>

	@foreach($files as $file)
		<div class="card">
			<div class="card-header" data-toggle="collapse" href="#track-{{ $file['random'] }}" data-file-name="{{ $file['filename'] }}">
				<i class="text-warning fa fa-lg fa-arrow-circle-right"></i>
				{{ $file['filename'] }}
			</div>
			<div class="card-body audio-upload-card-body collapse" id="track-{{ $file['random'] }}">
				<form>
					<div class="row form-group">
						<label class="col-sm-2 col-form-label" for="title-{{ $file['random'] }}">Title</label>
						<div class="col-sm-10">
							<input class="form-control" type="text" value="{{ $file['title'] }}" placeholder="Title" id="title-{{ $file['random'] }}">
						</div>
					</div>
					<div class="row form-group">
						<label class="col-sm-2 col-form-label" for="artist-{{ $file['random'] }}">Artist</label>
						<div class="col-sm-10">
							<input class="form-control" type="text" value="{{ $file['artist'] }}" placeholder="Artist" id="artist-{{ $file['random'] }}">
						</div>
					</div>
					<div class="row form-group">
						<label class="col-sm-2 col-form-label" for="album-{{ $file['random'] }}">Album</label>
						<div class="col-sm-10">
							<input class="form-control" type="text" value="{{ $file['album'] }}" placeholder="Album" id="album-{{ $file['random'] }}">
						</div>
					</div>
					<div class="row form-group">
						<label class="col-sm-2 col-form-label" for="origin-{{ $file['random'] }}">Origin</label>
						<div class="col-sm-10">
							<input class="form-control" type="text" value="{{ $file['origin'] }}" placeholder="Origin" id="origin-{{ $file['random'] }}" disabled>
						</div>
					</div>
					<div class="row form-group">
						<label class="col-sm-2 col-form-label">Audio Type</label>
						<div class="col-sm-10">
							<select class="form-control" name="type">
								<option value="1">Music</option>
								<option value="2">Jingle</option>
								<option value="3">Advert</option>
								<option value="4">Prerec</option>
							</select>
						</div>
					</div>
					<div class="row form-group">
						<div class="col-sm-10">
							<button class="btn btn-success btn-import" type="button">Import</button>
							<button class="btn btn-danger btn-delete" type="button">Delete</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	@endforeach

	<script src="/js/audio/upload.js"></script>
@endsection