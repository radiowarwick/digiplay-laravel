@extends('layouts.app')

@section('title', 'Playlist Index')

@section('breadcrumbs')
	{{ Breadcrumbs::render('playlist-index') }}
@endsection

@section('content')
	<h1>Playlists</h1>

	<button type="button" class="btn btn-warning">Create Playlist</button>

	<h2>Studio Playlists</h2>

	<table class="table table-responsive">
		<thead>
			<tr>
				<th></th>
				<th>Name</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			@foreach($studio as $playlist)
				<tr>
					<td>
						<a href="{{ route('playlist-view', $playlist->id) }}">
							<i class="fa fa-info-circle"></i>
						</a>
					</td>
					<td>{{ $playlist->name }}</td>
					<td></td>
				</tr>
			@endforeach
		</tbody>
	</table>

	<h2>Sustainer Playlists</h2>

	<table class="table table-responsive">
		<thead>
			<tr>
				<th></th>
				<th>Name</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			@foreach($sustainer as $playlist)
				<tr>
					<td>
						<a href="#">
							<i class="fa fa-info-circle"></i>
						</a>
					</td>
					<td>{{ $playlist->name }}</td>
					<td></td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection