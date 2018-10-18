@extends('layouts.app')

@section('title', 'Sustainer Admin')

@section('breadcrumbs')
	{{ Breadcrumbs::render('admin-sustainer-index') }}
@endsection

@section('content')
	<h1>Sustainer</h1>

	<div class="row">
		<div class="col-md-3">
			<div class="list-group">
				@foreach($playlists as $playlist)
					<div class="list-group-item" style="background:#{{ $playlist->colour->colour }};color:#{{ $playlist->colour->foreground() }}">
						{{ $playlist->name }}
					</div>
				@endforeach
			</div>
		</div>
		<div class="col-md-9">
			<table class="table table-bordered bg-white text-dark">
				<thead>
					<tr>
						<th></th>
						<th>Monday</th>
						<th>Tuesday</th>
						<th>Wednesday</th>
						<th>Thursday</th>
						<th>Friday</th>
						<th>Saturday</th>
						<th>Sunday</th>
					</tr>
				</thead>
				<tbody>
					@for($hour = 0, $i = 0; $hour <= 23; $hour++)
						<tr>
							<td>{{ $hour < 10 ? '0' . $hour : $hour }}:00</td>
							@for($day = 1; $day <= 7; $day++, $i++)
								<td class="text-center" style="background:#{{ $slots[$i]->playlist->colour->colour }};">
									@if(!is_null($slots[$i]->audioid))
										<i class="fa fa-clock-o"></i>
									@endif
								</td>
							@endfor
						</tr>
					@endfor
				</tbody>
			</table>
		</div>
	</div>
@endsection