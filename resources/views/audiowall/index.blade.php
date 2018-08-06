@extends('layouts.app')

@section('title', 'Audiowalls')

@section('breadcrumbs')
	{{ Breadcrumbs::render('audiowall-index') }}
@endsection

@section('content')
	<h1>Audiowalls</h1>

	<p>
		This page allows you to create and edit personal audiowalls for use in the studios. To select a wall for use in the studios click "Make Active" next to the wall of your choosing. 
	</p>

	<h2>Create</h2>

	@if($can_create)
		<form class="form-inline" method="POST" action="{{ route('audiowall-create') }}">
			{{ csrf_field() }}
			<input type="text" placeholder="Name" name="name" class="form-control mb-2 mr-sm-2">
			<button class="btn btn-warning mb-2" type="submit">Create</button>
		</form>

		@if($errors->any())
			@foreach ($errors->all() as $error)
				<p class="text-warning">{{ $error }}</p>
			@endforeach
		@endif
	@else
		<p>
			You are only allowed to own 2 audiowalls at a time. Please delete one if you wish to create a new one.
		</p>
	@endif

	<h2>Walls</h2>
	
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