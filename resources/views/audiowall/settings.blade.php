@extends('layouts.app')

@section('title', 'Audiowall Settings')

@section('breadcrumbs')
	{{ Breadcrumbs::render('audiowall-settings', $set) }}
@endsection

@section('content')
	<h1>Audiowall Settings</h1>
	<h2>{{ $set->name }}</h2>
	<p>
		<form class="form" method="POST" action="{{ route('audiowall-setting-name', $set->id) }}">
			{{ csrf_field() }}
			<h3>Name</h3>
			<div class="form-group">
				<input type="text" placeholder="Name" name="name" class="form-control" value="{{ $set->name }}">
			</div>
			<button class="btn btn-warning" type="submit">Save</button>
		</form>
	</p>
	<p>
		<h3>Permissions</h3>
		<table class="table table-responsive">
			<thead>
				<tr>
					<th>Username</th>
					<th>Name</th>
					<th>Permission</th>
					<th>Remove</th>
				</tr>
			</thead>
			<tbody>
				@foreach($set->permissions as $permission)
					<tr>
						<td>{{ $permission->user->username }}</td>
						<td>{{ $permission->user->name }}</td>
						<td>
							@if($permission->level < 4)
								<form class="form form-inline" action="{{ route('audiowall-setting-update', ['id' => $set->id, 'username' => $permission->user->username]) }}" method="POST">
									{{ csrf_field() }}
									<select class="form-control mb-2 mr-sm-2" name="level">
										<option value="1" {{ ($permission->level == 1) ? 'selected' : '' }}>User can view</option>
										<option value="2" {{ ($permission->level == 2) ? 'selected' : '' }}>User can edit</option>
										<option value="3" {{ ($permission->level == 3) ? 'selected' : '' }}>User has admin</option>
									</select>
									
									@if(auth()->user()->username != $permission->user->username)
										<div class="form-group mb-2 mr-sm-2">
											<button class="btn btn-warning" type="submit">Update</button>
										</div>
									@endif
								</form>
							@else
								Owner
							@endif
						</td>
						@if(auth()->user()->username != $permission->user->username and $permission->level < 4)
							<td>
								<a class="btn btn-danger" href="{{ route('audiowall-setting-remove', ['id' => $set->id, 'username' => $permission->user->username]) }}">Remove</a>
							</td>
						@else
							<td></td>
						@endif
					</tr>
				@endforeach
			</tbody>
		</table>

		<h4>Add User</h4>
		<form class="form-inline" method="POST" action="{{ route('audiowall-setting-add', $set->id) }}">
			{{ csrf_field() }}
			<input type="text" class="form-control mb-2 mr-sm-2" name="username" placeholder="Username">

			<select class="form-control mb-2 mr-sm-2" name="level">
				<option value="1" selected>User can view</option>
				<option value="2">User can edit</option>
				<option value="3">User has admin</option>
			</select>
			
			<div class="form-group mb-2 mr-sm-2">
				<button class="btn btn-warning" type="Submit" class="form-control">Add</button>
			</div>
		</form>

		@if($errors->any())
			@foreach ($errors->all() as $error)
				<p class="text-warning">{{ $error }}</p>
			@endforeach
		@endif

	</p>
@endsection