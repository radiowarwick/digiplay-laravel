@extends('layouts.app')

@section('title', 'Showplan Settings')

@section('breadcrumbs')
	{{ Breadcrumbs::render('showplan-settings', $showplan) }}
@endsection

@section('content')
	<h1>Showplan Settings</h1>
	<h2>{{ $showplan->name }}</h2>

	<p>
		<form class="form" method="POST" action="{{ route('showplan-setting-name', $showplan->id) }}">
			{{ csrf_field() }}
			<h3>Name</h3>
			<div class="form-group">
				<input type="text" placeholder="Name" name="name" class="form-control" value="{{ $showplan->name }}">
			</div>
			<button class="btn btn-warning" type="submit">Save</button>
		</form>
	</p>

	<p>
		<h3>Editors</h3>

		<table class="table table-responsive">
			<thead>
				<tr>
					<th>Username</th>
					<th>Name</th>
					<th>Remove</th>
				</tr>
			</thead>
			<tbody>
				@foreach($showplan->permissions as $permission)
					@if($permission->level < 2)
						<tr>
							<td>
								{{ $permission->user->username }}
							</td>
							<td>
								{{ $permission->user->name }}
							</td>
							<td>
								<a class="btn btn-danger" href="{{ route('showplan-setting-remove', ['id' => $showplan->id, 'username' => $permission->user->username]) }}">Remove</a>
							</td>
						</tr>
					@endif
				@endforeach
			</tbody>
		</table>

		<h4>Add Editor</h4>
		<form class="form-inline" method="POST" action="{{ route('showplan-setting-add', $showplan->id) }}">
			{{ csrf_field() }}
			<input type="text" class="form-control mb-2 mr-sm-2" name="username" placeholder="Username">
			
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