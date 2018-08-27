@extends('layouts.app')

@section('title', 'Showplan - ' . $showplan->name)

@section('breadcrumbs')
	{{ Breadcrumbs::render('showplan-edit', $showplan) }}
@endsection

@section('content')
	<h1>Showplan - {{ $showplan->name }}</h1>

	<div class="input-group">
		<input class="form-control" type="text" name="query" placeholder="Search...">
		<span class="input-group-btn">
			<button type="submit" class="btn btn-search btn-warning">
				Search
			</button>
		</span>
	</div>
	<div class="form-check form-check-inline">
		<input class="form-check-input" type="checkbox" id="studio-check-title" checked>
		<label class="form-check-label" for="studio-check-title">Title</label>
	</div>
	<div class="form-check form-check-inline">
		<input class="form-check-input" type="checkbox" id="studio-check-artist" checked>
		<label class="form-check-label" for="studio-check-artist">Artist</label>
	</div>
	<div class="form-check form-check-inline">
		<input class="form-check-input" type="checkbox" id="studio-check-album" checked>
		<label class="form-check-label" for="studio-check-album">Album</label>
	</div>

	<table class="table table-showplan table-responsive">
		<thead>
			<tr>
				<th></th>
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
					@if($item->audio->censor == "f")
						<td><i class="fa fa-music"></i></td>
					@else
						<td class="text-danger"><i class="fa fa-exclamation-circle"></i></td>
					@endif
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

	<div class="modal-search-results modal fade" role="dialog">
		<div class="modal-dialog modal-very-lg">
			<div class="modal-content bg-dark">
				<div class="modal-header">
					<h5 class="modal-title">Audio Search Results</h5>
					<button type="button" class="close text-warning" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<table class="table table-hover showplan-search-results">
						<thead>
							<tr>
								<th class="icon"></th>
								<th class="artist">Artist</th>
								<th class="name">Name</th>
								<th class="album">Album</th>
								<th class="length">Length</th>
								<th class="add">Add</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" src="/js/showplan/edit.js"></script>
@endsection