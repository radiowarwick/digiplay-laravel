@extends('layouts.app')

@section('title', 'Audio Upload')

@section('breadcrumbs')
	{{ Breadcrumbs::render('audio-upload') }}
@endsection

@section('content')
	<h1>Audio Upload</h1>

	<div class="row">
		<div class="col-sm-2">
			<form class="form-inline" id="form-upload">
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

	<script src="/js/audio/upload.js"></script>
@endsection