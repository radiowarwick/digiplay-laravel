@extends('layouts.app')

@section('title', 'Showplan - ' . $showplan->name)

@section('breadcrumbs')
	{{ Breadcrumbs::render('showplan-edit', $showplan) }}
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
				<tr data-item-id="{{ $item->id }}">
					<td>{{ $item->audio->artist->name }}</td>
					<td>{{ $item->audio->title }}</td>
					<td>{{ $item->audio->album->name }}</td>
					<td>{{ $item->audio->lengthString() }}</td>
					<td class="text-warning">
						<i class="fa fa-lg fa-arrow-circle-up showplan-move-up"></i>
						<i class="fa fa-lg fa-arrow-circle-down showplan-move-down"></i>
					</td>
					<td>
						<button class="btn btn-danger showplan-remove">Remove</button>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

	<script type="text/javascript" src="/js/showplan/edit.js"></script>
@endsection