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
				<button type="button" id="btn-upload" class="btn btn-warning md-2">Upload</button>
			</form>
		</div>
		<div class="col-sm-10">
			<div class="progress audio-progress">
				<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar">0%</div>
			</div>
		</div>
	</div>

	<div class="card-container">
		@foreach($files as $file)
			<div class="card audio-upload-card">
				<div class="card-header" data-toggle="collapse" href="#track-{{ $file['random'] }}">
					<i class="text-warning fa fa-lg fa-arrow-circle-right"></i>
					{{ preg_replace('/ [0-9]{1,4}\./', '.', $file['filename']) }}
				</div>
				<div class="card-body audio-upload-card-body collapse" id="track-{{ $file['random'] }}" data-filename="{{ $file['filename'] }}">
					<form>
						<div class="row form-group">
							<label class="col-sm-2 col-form-label" for="title-{{ $file['random'] }}">Title</label>
							<div class="col-sm-10">
								<input class="form-control" type="text" name="title" value="{{ $file['title'] }}" placeholder="Title" id="title-{{ $file['random'] }}">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-2 col-form-label" for="artist-{{ $file['random'] }}">Artist</label>
							<div class="col-sm-10">
								<input class="form-control" type="text" name="artist" value="{{ $file['artist'] }}" placeholder="Artist" id="artist-{{ $file['random'] }}">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-2 col-form-label" for="album-{{ $file['random'] }}">Album</label>
							<div class="col-sm-10">
								<input class="form-control" type="text" name="album" value="{{ $file['album'] }}" placeholder="Album" id="album-{{ $file['random'] }}">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-2 col-form-label" for="censor-{{ $file['random'] }}">Censored</label>
							<div class="col-sm-10">
								<div class="form-check">
									<input class="form-check-input" type="checkbox" name="censored" value="t" id="censor-{{ $file['random'] }}">
									<label class="form-check-label" for="censor-{{ $file['random'] }}">
										Does this track have explicit content?
									</label>
								</div>
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
							<label class="col-sm-2 col-form-label">Length</label>
							<div class="col-sm-10">
								{{ $file['length'] }}
							</div>
						</div>
						<div class="row form-group">
							<div class="col-sm-12 text-danger error">
							</div>
						</div>
						<div class="row form-group">
							<div class="col-sm-12 text-danger bitrate" {!! ($file['acceptable_bitrate']) ? "style=\"display:none;\"" : "" !!}>
								The track is below the recommended bitrate!
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
	</div>

	<div class="card audio-upload-card d-none audio-upload-card-template">
		<div class="card-header" data-toggle="collapse" href="#track-">
			<i class="text-warning fa fa-lg fa-arrow-circle-right"></i>
			<span class="file-name"></span>
		</div>
		<div class="card-body audio-upload-card-body collapse" id="track-" data-filename>
			<form>
				<div class="row form-group">
					<label class="col-sm-2 col-form-label" for="title-">Title</label>
					<div class="col-sm-10">
						<input class="form-control" type="text" name="title" value placeholder="Title" id="title-">
					</div>
				</div>
				<div class="row form-group">
					<label class="col-sm-2 col-form-label" for="artist-">Artist</label>
					<div class="col-sm-10">
						<input class="form-control" type="text" name="artist" value placeholder="Artist" id="artist-">
					</div>
				</div>
				<div class="row form-group">
					<label class="col-sm-2 col-form-label" for="album-">Album</label>
					<div class="col-sm-10">
						<input class="form-control" type="text" name="album" value placeholder="Album" id="album-">
					</div>
				</div>
				<div class="row form-group">
					<label class="col-sm-2 col-form-label" for="censor-">Censored</label>
					<div class="col-sm-10">
						<div class="form-check">
							<input class="form-check-input" type="checkbox" name="censored" value="t" id="censor-">
							<label class="form-check-label" for="censor-">
								Does this track have explicit content?
							</label>
						</div>
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
					<label class="col-sm-2 col-form-label">Length</label>
					<div class="col-sm-10">
						<span class="length"></span>
					</div>
				</div>
				<div class="row form-group">
					<div class="col-sm-12 text-danger error">
					</div>
				</div>
				<div class="row form-group">
					<div class="col-sm-12 text-danger bitrate" style="display:none;">
						The track is below the recommended bitrate!
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

	<script src="/js/audio/upload.js"></script>
@endsection