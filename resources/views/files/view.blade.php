@extends('layouts.app')

@section('title', 'File Explorer')

@section('breadcrumbs')
	{{ Breadcrumbs::render('audio-search') }}
@endsection

@section('content')
	<script src="/js/files/upload.js"></script>
	<h1>File Explorer</h1>

	@php
		$part_url = '/files';
	@endphp
	<a href="{{ $part_url }}">My Files</a>
	@foreach(explode('/', $path) as $part)
		@if(!empty($part))
			@php
				$part_url_previous = $part_url;
				$part_url .= '/' . $part;
			@endphp
			/
			<a href="{{ $part_url }}">{{ $part }}</a>
		@endif
	@endforeach

	<table class="table table-sm">
		<thead>
			<tr>
				<th></th>
				<th>Name</th>
				<th>Upload</th>
				<th>Size</th>
				<th>Delete</th>
			</tr>
		</thead>
		<tbody>
			@if($parent)
				<tr>
					<td>
						<i class="fa fa-folder-open-o"></i>
					</td>
					<td>
						<a href="{{ $part_url_previous }}">
							Parent Directory
						</a>
					</td>
					<td></td>
					<td></td>
				</tr>
			@endif
			@foreach($directories as $directory)
				<tr>
					<td>
						<i class="fa {{ $directory['icon'] }}"></i>
					</td>
					<td>
						<a href="{{ route('file-folder', $directory['path']) }}">
							{{ $directory['name'] }}
						</a>
					</td>
					<td></td>
					<td>
						{{ $directory['size'] }}	
					</td>
					<td>
						<i class="fa fa-times-circle text-warning"></i>
					</td>
				</tr>
			@endforeach
			@foreach($files as $file)
				<tr>
					<td>
						<i class="fa {{ $file['icon'] }}"></i>
					</td>
					<td>
						<a href="{{ route('file-download', $file['path']) }}">
							{{ $file['name'] }}
						</a>
					</td>
					<td>
						{{ \Carbon\Carbon::createFromTimestamp($file['upload'])->format('d/m/Y H:i') }}
					</td>
					<td>
						{{ $file['size'] }}
					</td>
					<td>
						<i class="fa fa-times-circle text-warning"></i>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

	{{ $used }} used out of 2 GB

	<h2>Create Directory</h2>
	<form class="form-inline" action="{{ route('file-create-directory', $path) }}" method="POST">
		{{ csrf_field() }}
		<div class="form-group mb-2 mr-2">
			<input type="text" name="directory" class="form-control" placeholder="Name">
		</div>
		<button class="btn btn-warning mb-2">Create</button>
	</form>

	@if($errors->any())
		@foreach($errors->all() as $error)
			<p class="text-warning">
				{{ $error }}
			</p>
		@endforeach
	@endif

	<h2>Upload File</h2>
	<div class="row">
		<div class="col-sm-2">
			<form class="form-inline" id="form-upload" enctype="multipart/form-data">
				{{ csrf_field() }}
				<label class="btn btn-primary md-2 mr-md-2">
					<i class="fa fa-upload"></i>
					File
					<input type="file" name="file" hidden>
				</label>
				<button type="button" id="btn-upload" class="btn btn-warning md-2" disabled>Upload</button>
			</form>
		</div>
		<div class="col-sm-10">
			<div class="progress audio-progress">
				<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar">0%</div>
			</div>
		</div>
	</div>
@endsection
