@extends('layouts.app')

@section('title', 'Home')

@section('breadcrumbs')
	{{ Breadcrumbs::render('index') }}
@endsection

@section('content')
	<h1>RAW Digiplay</h1>

	@include('forms.audio-search')

	<div class="row">
		<div class="col-md-4">
			<h3>Music Library</h3>
			<p>
				<strong>Tracks Stored</strong> - {{ number_format(\App\Audio::count()) }}
			</p>
			<p>
				<strong>Playlisted Tracks</strong> - {{ number_format(\App\PlaylistAudio::distinct('audioid')->count('audioid')) }}
			</p>
			@if(auth()->user()->hasPermission('Can upload audio'))
				<p>
					<a href="{{ route('audio-upload') }}" class="btn btn-warning btn-block">Upload Audio</a>
				</p>
			@endif
		</div>
		<div class="col-md-4">
			<h3>Newest Tracks</h3>
			<table class="table table-striped table-sm">
				<tbody>
					@foreach(\App\Audio::tracks()->orderby('creation_date', 'DESC')->limit(5)->get() as $l)
						<tr>
							<td class="icon">
								<a href="{{ route('audio-view', $l->id) }}">
									<i class="fa fa-info-circle"></i>
								</a>
							</td>
							<td>
								{{ $l->title }} by {{ $l->artist->name }}
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			<a href="{{ route('audio-index') }}" class="btn btn-warning btn-block">View More</a>
		</div>
		<div class="col-md-4">
			<h3>Useful Links</h3>

			<a href="{{ route('showplan-index') }}" class="btn btn-warning btn-block">Create Showplans</a>
			<a href="{{ route('audiowall-index') }}" class="btn btn-warning btn-block">Create Audiowalls</a>
			@if(auth()->user()->hasPermission('Sustainer admin'))
				<a href="{{ route('admin-sustainer-index') }}" class="btn btn-warning btn-block">Sustainer Admin</a>
			@endif
			@if(auth()->user()->hasPermission('Playlist editor'))
				<a href="{{ route('playlist-index') }}" class="btn btn-warning btn-block">View Playlists</a>
			@endif
		</div>
	</div>
@endsection