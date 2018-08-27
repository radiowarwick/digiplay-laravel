@extends('layouts.app')

@section('title', 'Showplans')

@section('breadcrumbs')
	{{ Breadcrumbs::render('showplan-index') }}
@endsection

@section('content')
	<h1>Showplans</h1>

	<p>
		Showplans allow you to create a playlist of songs before your shows. Simply create a showplan, search for songs and add them. When you get into either studio you can load up your showplan after you've logged in.
	</p>

	<p>
		Please note that if your showplan contains songs marked as explicit then they will not load before the watershed!
	</p>

	@if($can_create)
		<form class="form-inline" method="POST" action="{{ route('showplan-create') }}">
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
			You are only allowed to have 5 showplans at a time. Please delete one if you wish to create a new one.
		</p>
	@endif

	<table class="table table-responsive">
		<thead>
			<tr>
				<th></th>
				<th>Name</th>
				<th>Edit</th>
				<th>Settings</th>
				<th>Delete</th>
			</tr>
		</thead>
		<tbody>
			@foreach($showplans as $showplan)
				<tr>
					<td>
						{{ $showplan->name }}
					</td>
					<td>
						<a class="btn btn-warning" href="{{ route('showplan-edit', $showplan->id) }}">Edit</a>
					</td>
					<td>
						@if($showplan->isOwner(auth()->user()))
							<a class="btn btn-warning" href="{{ route('showplan-settings', $showplan->id) }}">Settings</a>
						@endif
					</td>
					<td>
						@if($showplan->id > 4)
							<a class="btn btn-danger" href="{{ route('showplan-delete', $showplan->id) }}">Delete</a>
						@endif
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection