@extends('layouts.app')

@section('title', 'File Explorer')

@section('breadcrumbs')
	{{ Breadcrumbs::render('audio-search') }}
@endsection

@section('content')
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
					<td>X</td>
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
					<td>X</td>
				</tr>
			@endforeach
		</tbody>
	</table>

	{{ $used }} used out of 2 GB
@endsection
