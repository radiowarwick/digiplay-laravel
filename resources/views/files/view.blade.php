@extends('layouts.app')

@section('title', 'File Explorer')

@section('breadcrumbs')
	{{ Breadcrumbs::render('audio-search') }}
@endsection

@section('content')
	<h1>File Explorer</h1>

	<table class="table table-sm">
		<thead>
			<tr>
				<th></th>
				<th>Name</th>
				<th>Size</th>
				<th>Delete</th>
			</tr>
		</thead>
		<tbody>
			@if($parent)
				<tr>
					<td>
						<i class="fa fa-folder-open"></i>
					</td>
					<td>
						<a href=".">
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
						<i class="fa fa-folder"></i>
					</td>
					<td>
						<a href="{{ route('file-folder', $path . $directory) }}">
							{{ $directory }}
						</a>
					</td>
					<td></td>
					<td>X</td>
				</tr>
			@endforeach
			@foreach($files as $file)
				<tr>
					<td>
					</td>
					<td>
						<a href="{{ route('file-download', $path . $file) }}">
							{{ $file }}
						</a>
					</td>
					<td></td>
					<td>X</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection
