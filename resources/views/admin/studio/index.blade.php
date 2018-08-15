@extends('layouts.app')

@section('title', 'Studio Logins')

@section('breadcrumbs')
	{{ Breadcrumbs::render('admin-studio-index') }}
@endsection

@section('content')
	<h1>Studio Logins</h1>

	<p>
		Search to find when people have logged into each studio.
		<form method="POST">
			{{ csrf_field() }}
			<div class="form-group row">
				<label class="col-sm-2 col-form-label" for="date">Date</label>
				<div class="col-sm-4">
					<input class="form-control" type="date" name="date" value="{{ $time }}" max="{{ date('Y-m-d') }}">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label" for="location">Location</label>
				<div class="col-sm-4">
					<select class="form-control" name="location">
						<option value="1" default>Studio 1</option>
						<option value="2">Studio 2</option>
					</select>
				</div>
			</div>
			
			<button type="submit" class="btn btn-warning">Search</button>
		</form>
	</p>

	@if(count($logins) > 0)
		<table class="table table-responsive">
			<thead>
				<tr>
					<th>Username</th>
					<th>Name</th>
					<th>Login</th>
					<th>Logout</th>
				</tr>
			</thead>
			<tbody>
				@foreach($logins as $login)
					<tr>
						<td>{{ $login->username }}</td>
						<td>{{ $login->user->name }}</td>
						<td>{{ $login->created_at->format('d/m/y H:i:s') }}</td>
						<td>{{ (is_null($login->logout_at)) ? '' : $login->logout_at->format('d/m/y H:i:s') }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@else
		<p>
			No results found in that studio and that date.
		</p>
	@endif
@endsection