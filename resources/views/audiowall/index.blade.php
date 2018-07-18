@extends('layouts.app')

@section('title', 'Audiowalls')

@section('breadcrumbs')
	{{ Breadcrumbs::render('audiowall-index') }}
@endsection

@section('content')
	<table class="table">
		<thead>
			<tr>
				<th>Name</th>
				<th>Active</th>
			</tr>
		</thead>
		<tbody>
			@foreach($sets as $set)
				<tr>
					<td>{{ $set->name }}</td>
					<td>
						@if($set->id == $current_audiowall_id)
							<button class="btn btn-success" href="#">Currently Active</button>
						@else
							<a class="btn btn-warning" href="{{ route('audiowall-activate', $set->id) }}">Make Active</a>
						@endif
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection