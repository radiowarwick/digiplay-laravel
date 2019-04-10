@extends('layouts.app')

@section('title', 'Sustainer Admin')

@section('breadcrumbs')
	{{ Breadcrumbs::render('admin-sustainer-index') }}
@endsection

@section('content')
	<script src="/js/sustainer/schedule.js"></script>
	
	<h1>Sustainer Management</h1>
	
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
	
	<h2>Schedule a Prerecord</h2>
	
	<form method="POST">
		{{ csrf_field() }}

		<div class="form-group">
			<label class="form-control-label">
				Prerecord
			</label>
			<select id="prerec-select" class="form-control selectpicker">
			</select>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="date">Date</label>
					<input class="form-control" type="date" name="date" id="date" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}">
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group">
					<label for="time">Time</label>
					<select class="form-control" id="time" name="time">
						@for($i = 0; $i < 24; $i++)
							<option value="{{ $i }}">
								{{ $i < 10 ? '0' . $i : $i }}:00
							</option>
						@endfor
					</select>
				</div>
			</div>
		</div>

		<button class="btn btn-success">Schedule</button>
	</form>
@endsection