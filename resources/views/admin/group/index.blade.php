@extends('layouts.app')

@section('title', 'Admin')

@section('content')
	<h1>Group Admin</h1>

	<h2>Manage Groups</h2>

	<table class="table">
		<thead>
			<tr>
				<th>Name</th>
				<th>Membership</th>
				<th>Permissions</th>
				<th>Rename</th>
				<th>Delete</th>
			</tr>
		</thead>
		<tbody>
			@foreach($groups as $group)
				<tr>
					<td>{{ $group->name }}</td>
					<td><a href="#" class="btn btn-warning">Membership</a></td>
					<td><a href="#" class="btn btn-warning">Permissions</a></td>

					@if($group->can_edit)
						<td><a href="#" class="btn btn-warning">Rename</a></td>
						<td><a href="#" class="btn btn-danger">Delete</a></td>
					@else
						<td></td>
						<td></td>
					@endif
				</tr>
			@endforeach
		</tbody>
	</table>

	<h2>Create Group</h2>
	<form class="form-inline" method="POST" action="{{ route('admin-group-create') }}">
		{{ csrf_field() }}

		<input type="text" name="name" class="form-control mb-2 mr-sm-2" placeholder="Name">

		<button class="btn btn-warning mb-2">Create</button>
	</form>

	@if($errors->any())
		@foreach($errors->all() as $error)
			<p class="text-danger">{{ $error }}</p>
		@endforeach
	@endif
@endsection