@extends('layouts.app')

@section('title', 'Audiowalls')

@section('breadcrumbs')
	{{ Breadcrumbs::render('audiowall-index') }}
@endsection

@section('content')
	<table class="table table-responsive">
		<thead>
			<tr>
				<th>Name</th>
				<th>Active</th>
				<th>Settings</th>
				<th>Delete</th>
			</tr>
		</thead>
		<tbody>
			@foreach($sets as $set)
				@if($set->hasView(Auth::user()))
					<tr>
						<td>
							<a href="{{ route('audiowall-view', $set->id) }}">{{ $set->name }}</a>
						</td>
						<td>
							@if($set->id == $current_audiowall_id)
								<button class="btn btn-success" href="#">Currently Active</button>
							@else
								<a class="btn btn-warning" href="{{ route('audiowall-activate', $set->id) }}">Make Active</a>
							@endif
						</td>
							@if($set->hasAdmin(Auth::user()))
								<td>
									<a class="btn btn-warning" href="{{ route('audiowall-settings', $set->id) }}">Settings</a>
								</td>
								<td>
									{{-- 198 is the ID of the main station audiowall --}}
									@if($set->id != 198)
										<a class="btn btn-danger" href="#">Delete</a>
									@endif
								</td>
							@else
								<td></td><td></td>
							@endif
					</tr>
				@endif
			@endforeach
		</tbody>
	</table>
@endsection