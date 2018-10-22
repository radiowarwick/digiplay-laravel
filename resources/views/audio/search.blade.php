@extends('layouts.app')

@section('title', 'Audio Search')

@section('breadcrumbs')
	{{ Breadcrumbs::render('audio-search') }}
@endsection

@section('content')
	@section('q', $q)

	@include('forms.audio-search')

	<h3>{{ $total }} results</h3>
	
	@if($total > 0)
		<table class="table table-striped table-responsive">
			<thead>
				<tr>
					<th class="icon"></th>
					<th class="title">Title</th>
					<th class="artist">Artist</th>
					<th class="album">Album</th>
					<th class="length">Length</th>
					<th class="type">Type</th>
					@if(auth()->user()->hasPermission('Playlist editor'))
						<th class="icon"></th>
					@endif
				</tr>
			</thead>
			<tbody>
				@foreach($results as $result)
					<tr {!! ($result->censor == 't') ? 'class="bg-danger"' : '' !!}>
						<td class="icon">
							<a href="{{ route('audio-view', $result->id) }}">
								<i class="fa fa-info-circle"></i>
							</a>
						</td>
						<td class="title">{{ $result->title }}</td>
						<td class="artist">{{ $result->artist->name }}</td>
						<td class="album">{{ $result->album->name }}</td>
						<td class="length">{{ $result->lengthString() }}</td>
						<td class="title">{{ $result->getTypeString() }}</td>
						@if(auth()->user()->hasPermission('Playlist editor'))
							<td class="icon">
								<i class="fa fa-plus-circle playlist-change text-warning" data-audio-id="{{ $result->id }}"></i>
							</td>
						@endif
					 </td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@endif

	{{ $results->appends(['q' => $q])->links() }}

	@if(auth()->user()->hasPermission('Playlist editor'))
		{{ csrf_field() }}

		@include('layouts.playlist')
	@endif
@endsection
