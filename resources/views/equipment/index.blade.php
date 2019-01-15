@extends('layouts.app')

@section('title', 'Equipment Booking')

@section('breadcrumbs')
	{{ Breadcrumbs::render('index') }}
@endsection

@section('content')
	<h1>Equipment Bookings</h1>

	<h4>Week Beginning</h4>
	<div class="btn-group mb-2">
		@for($i = 0, $j = $start_of_this_week->copy(); $i < 5; $i++, $j = $j->addWeeks(1))
				<a class="btn {{ ($j == $start_of_week) ? 'btn-dark' : 'btn-warning' }}" href="{{ route('equipment-date', $j->format('Y-m-d')) }}">
					{{ $j->format('jS M') }}
				</a>
		@endfor
	</div>

	<h4>Day of Week</h4>
	<div class="btn-group mb-2">
		@for($i = 0, $j = $start_of_week->copy(); $i < 7; $i++, $j = $j->addDays(1))
			<a class="btn {{ ($j == $date) ? 'btn-dark' : 'btn-warning' }} {{ ($j->lt($today)) ? 'disabled' : '' }}" href="{{ route('equipment-date', $j->format('Y-m-d')) }}">
				{{ $j->format('D jS') }}
			</a>
		@endfor
	</div>

	<table class="table table-responsive">
		<thead>
			<tr>
				<th>Time</th>
				@foreach($ITEMS as $item)
					<th>{{ $item }}</th>
				@endforeach
			</tr>
		</thead>
		@for($i = $BOOKINGS_START; $i <= $BOOKINGS_END; $i++)
			<tbody>
				<tr>
					<td>{{ ($i <= 12) ? $i : ($i - 12) }}:00 {{ ($i <= 12) ? 'am' : 'pm' }}</td>
					<td></td>
				</tr>
			</tbody>
		@endfor
	</table>
@endsection