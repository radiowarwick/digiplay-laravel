@extends('layouts.app')

@section('title', 'Playlist Index')

@section('breadcrumbs')
	{{ Breadcrumbs::render('playlist-index') }}
@endsection

@section('content')
	<h1>Playlists</h1>

	<a href="{{ route('playlist-create') }}" class="btn btn-warning">Create Playlist</a>

	<h2>Studio Playlists</h2>

	<table class="table table-responsive">
		<thead>
			<tr>
				<th class="icon"></th>
				<th>Name</th>
				<th>Tracks</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			@foreach($studio as $playlist)
				<tr>
					<td class="icon">
						<a href="{{ route('playlist-view', $playlist->id) }}">
							<i class="fa fa-info-circle"></i>
						</a>
					</td>
					<td>{{ $playlist->name }}</td>
					<td>{{ sizeof($playlist->audio) }}
					<td class="icon">
						<a href="{{ route('playlist-edit', $playlist->id) }}">
							<i class="fa fa-pencil-square"></i>
						</a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

	<h2>Sustainer Playlists</h2>

	<table class="table table-responsive">
		<thead>
			<tr>
				<th class="icon"></th>
				<th>Name</th>
				<th>Tracks</th>
				<th class="icon"></th>
			</tr>
		</thead>
		<tbody>
			@foreach($sustainer as $playlist)
				<tr>
					<td class="icon">
						<a href="#">
							<i class="fa fa-info-circle"></i>
						</a>
					</td>
					<td>{{ $playlist->name }}</td>
					<td>{{ sizeof($playlist->audio) }}
					<td class="icon">
						<a href="{{ route('playlist-edit', $playlist->id) }}">
							<i class="fa fa-pencil-square"></i>
						</a>
					</td>

				</tr>
			@endforeach
		</tbody>
	</table>
@endsection