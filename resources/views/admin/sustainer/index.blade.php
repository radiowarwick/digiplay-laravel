@extends('layouts.app')

@section('title', 'Sustainer Admin')

@section('breadcrumbs')
	{{ Breadcrumbs::render('admin-sustainer-index') }}
@endsection

@section('content')
	{{-- JS Script --}}
	<script src="/js/sustainer/schedule.js"></script>
	
	<h1>Sustainer Management</h1>

	<h2>Schedule a Prerecord</h2>

	<div class="input-group">
		<input class="form-control" type="text" name="query" placeholder="Search...">
		<span class="input-group-btn">
			<button type="submit" class="btn btn-search btn-warning">
				Search
			</button>
		</span>
	</div>

	<br>

	<form action="{{ route('admin-sustainer-add') }}" method="POST">
		{{ csrf_field() }}

		<div class="form-group">
			<label>Prerecord</label>
			<p>
				<strong id="prerecord-title">
					Please search and select a prerecord
				</strong>
			</p>
			<input type="hidden" id="prerecord-id" name="prerecord-id">
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="date">Date</label>
					{{-- Sets minimum attribute so can't schedule in the past --}}
					<input class="form-control" type="date" name="date" id="date" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}">
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group">
					<label for="time">Time</label>
					<select class="form-control" id="time" name="time">
						{{-- Loop through each hour and add an option for each --}}
						@for($i = 0; $i < 24; $i++)
							<option value="{{ $i }}">
								{{ $i < 10 ? '0' . $i : $i }}:00
							</option>
						@endfor
					</select>
				</div>
			</div>
		</div>

		@foreach($errors->all() as $error)
			<p class="text-danger">
				{{ $error }}
			</p>
		@endforeach

		<button class="btn btn-success">Schedule</button>
	</form>
	
	<h2>Upcoming Prerecords</h2>

	<table class="table table-responsive">
		<thead>
			<tr>
				<th>Date</th>
				<th>Time</th>
				<th>Name</th>
				<th>Un-schedule</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>

	{{-- Search box --}}
	<div class="modal-search-results modal fade" role="dialog">
		<div class="modal-dialog modal-very-lg">
			<div class="modal-content bg-dark">
				<div class="modal-header">
					<h5 class="modal-title">Prerecord Search Results</h5>
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
								<th class="add">Select</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@endsection