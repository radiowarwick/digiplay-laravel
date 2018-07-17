@extends('layouts.app')

@section('title', 'Group Membership')

@section('breadcrumbs')
	{{ Breadcrumbs::render('admin-group-members', $group->id) }}
@endsection

@section('content')
	<h1>Group Membership</h1>
	<h2>{{ $group->name }}</h2>

	<table class="table table-lg">
		<thead>
			<tr>
				<th>Name</th>
				<th>Username</th>
				<th>Remove</th>
			</tr>
		</thead>
		<tbody>
			@foreach($members as $member)
				<tr>
					<td>{{ $member->name }}</td>
					<td>{{ $member->username }}</td>
					<td><a href="{{ route('admin-group-member-remove', ['id' => $group->id, 'username' => $member->username]) }}" class="btn btn-large btn-danger">Remove</a></td>
				</tr>
			@endforeach
		</tbody>
	</table>
		
	<form action="{{ route('admin-group-member-add-post', $group->id) }}" class="form-inline" method="POST">
		{{ csrf_field() }}

		<input type="text" class="form-control mb-2 mr-sm-2" name="username" placeholder="Username">
		
		<div class="input-group mb-2 mr-sm-2">
			<button class="btn btn-large btn-warning">Add</button>
		</div>

		<a href="{{ route('admin-group-index') }}" class="btn btn-large btn-danger mb-2">Cancel</a>
	</form>

	@if($errors->any())
		@foreach($errors->all() as $error)
			<p class="text-danger">{{ $error }}</p>
		@endforeach
	@endif
@endsection