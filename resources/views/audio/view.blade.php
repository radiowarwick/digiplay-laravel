@extends('layouts.app')

@section('title', 'Track View - ' . $audio->title)

@section('breadcrumbs')
	{{ Breadcrumbs::render('audio-view', $audio) }}
@endsection

@section('content')
	<script src="/js/audio/view.js"></script>

	<h1>Track View</h1>

	<div class="row audio-player">
		<div id="wavesurfer">
			<div class="progress audio-progress">
				<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"></div>
			</div>			
		</div>
		<div id="wavesurfer-timeline" style="display:none;"></div>

		<div class="btn-group mr-sm-3">
			<button type="button" id="btn-backward" class="btn btn-sm btn-warning">
				<i class="fa fa-backward"></i>
			</button>
			<button type="button" id="btn-play-pause" class="btn btn-sm btn-warning">
				<i class="fa fa-play"></i>
			</button>
			<button type="button" id="btn-forward" class="btn btn-sm btn-warning">
				<i class="fa fa-forward"></i>
			</button>
		</div>

		@if($canEdit)
			<div class="btn-group">
				<button type="button" id="btn-set-vocal-in" class="btn btn-sm btn-success" data-seconds="{{ $audio->getVocalIn() }}">
					Set Vocal In
				</button>
				<button type="button" id="btn-set-vocal-out" class="btn btn-sm btn-danger" data-seconds="{{ $audio->getVocalOut() }}">
					Set Vocal Out
				</button>
			</div>
		@endif
	</div>

	<div class="row">
		<div class="col-sm-8">
			<div class="form-group">
				<label for="title">Title</label>
				<input type="text" id="title" class="form-control" value="{{ $audio->title }}" {{ (!$canEdit) ? 'disabled' : '' }}>
			</div>
			<div class="form-group">
				<label for="artist">Artist</label>
				<input type="text" id="artist" class="form-control" value="{{ $audio->artist->name }}" {{ (!$canEdit) ? 'disabled' : '' }}>
			</div>
			<div class="form-group">
				<label for="album">Album</label>
				<input type="text" id="album" class="form-control" value="{{ $audio->album->name }}" {{ (!$canEdit) ? 'disabled' : '' }}>
			</div>
			<div class="form-group">
				<label for="type">Audio Type</label>
				<select class="form-control" id="type" {{ (!$canEdit) ? 'disabled' : '' }}>
					<option value="1" {{ $audio->type == 1 ? 'selected' : '' }}>Music</option>
					<option value="2" {{ $audio->type == 2 ? 'selected' : '' }}>Jingle</option>
					<option value="3" {{ $audio->type == 3 ? 'selected' : '' }}>Advert</option>
					<option value="4" {{ $audio->type == 4 ? 'selected' : '' }}>Prerec</option>
				</select>
			</div>
			<div class="form-group">
				<div class="form-check">
					<input class="form-check-input" id="censor" type="checkbox" {{ $audio->censor == 't' ? 'checked' : '' }} {{ (!$canEdit) ? 'disabled' : '' }}>
					<label for="censor" class="form-check-label">Explicit</label>
				</div>
			</div>

			@if($canEdit)
				<div class="form-group">
					{{ csrf_field() }}
					<button type="submit" id="btn-update" class="btn btn-success">Update</button>
					<button type="button" id="btn-delete" class="btn btn-danger" {!! $audio->audioDir->dirid == 3 ? 'style="display:none;"' : '' !!}>Delete</button>
					<button type="button" id="btn-restore" class="btn btn-danger" {!! $audio->audioDir->dirid == 2 ? 'style="display:none;"' : '' !!}>Restore</button>
				</div>
			@endif

			<div class="form-group text-danger error">
			</div>
			<div class="form-group text-success success">
			</div>
			<div class="form-group text-warning restore" {!! $audio->audioDir->dirid == 2 ? 'style="display:none;"' : '' !!}>
				This track is in the bin. Click restore to bring it back.
			</div>
		</div>
		<div class="col-sm-4">
			<div class="row">
				<div class="col-sm-6">
					<strong>Origin</strong>
				</div>
				<div class="col-sm-6">{{ $audio->origin }}</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<strong>Length</strong>
				</div>
				<div class="col-sm-6">{{ $audio->lengthString() }}</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<strong>Upload</strong>
				</div>
				<div class="col-sm-6">{{ date("d/m/Y H:i", $audio->import_date) }}</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<strong>Vocal In</strong>
				</div>
				<div class="col-sm-6" id="vocal-in">{{ $vocalIn }}</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<strong>Vocal Out</strong>
				</div>
				<div class="col-sm-6" id="vocal-out">{{ $vocalOut }}</div>
			</div>
			@if($canEdit)
				<div class="row mb-sm-3">
					<a href="{{ route('audio-download', $audio->id) }}" class="btn btn-warning btn-block">
						<i class="fa fa-download"></i>
						Download Flac
					</a>
				</div>
			@endif
			@if(auth()->user()->hasPermission('Playlist editor'))
				<div class="row">
					<button type="button" data-audio-id="{{ $audio->id }}" class="btn btn-primary btn-block playlist-change">
						<i class="fa fa-th-list"></i>
						Playlists
					</button>
				</div>

				@include('layouts.playlist')
			@endif
		</div>
	</div>
@endsection