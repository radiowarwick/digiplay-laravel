@extends('layouts.app')

@section('title', 'Playlist View')

@section('breadcrumbs')
	{{ Breadcrumbs::render('playlist-view', $playlist) }}
@endsection

@section('content')
	<script src="/js/audio/playlist.js"></script>

	{{ csrf_field() }}

	<h1>{{ $playlist->name }}</h1>

	<table class="table table-responsive">
		<thead>
			<tr>
				<th class="icon"></th>
				<th>Title</th>
				<th>Artist</th>
				<th>Album</th>
				<th class="icon"></th>
			</tr>
		</thead>
		<tbody>
			@foreach($playlistAudio as $pa)
				<tr>
					<td>
						<a href="{{ route('audio-view', $pa->audio->id) }}">
							<i class="fa fa-info-circle"></i>
						</a>
					</td>
					<td>{{ $pa->audio->title }}</td>
					<td>{{ $pa->audio->artist->name }}</td>
					<td>{{ $pa->audio->album->name }}</td>
					<td>
						<i class="fa fa-times-circle text-danger remove-track" data-state="ready" data-id="{{ $pa->id }}" data-placement="left" data-content="Delete track from playlist? Click again to confirm."></i>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

	{{ $playlistAudio->links() }}
@endsection