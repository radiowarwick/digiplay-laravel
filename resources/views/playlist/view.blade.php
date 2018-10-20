@extends('layouts.app')

@section('title', 'Playlist View')

@section('breadcrumbs')
	{{ Breadcrumbs::render('playlist-view', $playlist) }}
@endsection

@section('content')
	<h1>{{ $playlist->name }}</h1>

	<table class="table table-responsive">
		<thead>
			<tr>
				<th class="icon"></th>
				<th>Title</th>
			</tr>
		</thead>
		<tbody>
			@foreach($playlist->audio as $audio)
				<tr>
					<td>
						<a href="#">
							<i class="fa fa-info-circle"></i>
						</a>
					</td>
					<td>{{ $audio->title }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection