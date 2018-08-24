@extends('layouts.app')

@section('title', 'Showplan - ' . $showplan->name)

@section('breadcrumbs')
	{{ Breadcrumbs::render('showplan-index') }}
@endsection

@section('content')
	<h1>Showplan - {{ $showplan->name }}</h1>

	<table class="table table-responsive">
		<thead>
			<tr>
				<th>Artist</th>
				<th>Title</th>
				<th>Album</th>
				<th>Length</th>
				<th>Move</th>
				<th>Remove</th>
			</tr>
		</thead>
		<tbody>
			@foreach($showplan->items as $item)
				<tr>
					<td>{{ $item->audio->artist->name }}</td>
					<td>{{ $item->audio->title }}</td>
					<td>{{ $item->audio->album->name }}</td>
					<td>{{ $item->audio->lengthString() }}</td>
					<td></td>
					<td></td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection