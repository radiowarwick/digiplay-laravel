@extends('layouts.app')

@section('title', 'Admin')

@section('breadcrumbs')
	{{ Breadcrumbs::render('admin-group-index') }}
@endsection

@section('content')
	<h1>Group Admin</h1>

	<h2>Manage Groups</h2>

	<table class="table">
		<thead>
			<tr>
				<th>Name</th>
				<th>Membership</th>
				<th>Permissions</th>
			</tr>
		</thead>
		<tbody>
			@foreach($groups as $group)
				<tr>
					<td>{{ $group->name }}</td>
					<td><a href="{{ route('admin-group-members', $group->id) }}" class="btn btn-warning">Membership</a></td>
					<td><a href="{{ route('admin-group-permission', $group->id) }}" class="btn btn-warning">Permissions</a></td>
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