@extends('layouts.app')

@section('title', 'Showplans')

@section('breadcrumbs')
	{{ Breadcrumbs::render('showplan-index') }}
@endsection

@section('content')
	<h1>Showplans</h1>

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

	<table class="table table-responsive">
		<thead>
			<tr>
				<th>Name</th>
				<th>Edit</th>
				<th>Settings</th>
				<th>Delete</th>
			</tr>
		</thead>
		<tbody>
			@foreach($showplans as $showplan)
				<tr>
					<td>{{ $showplan->name }}</td>
					<td>
						<a class="btn btn-warning" href="#">Edit</a>
					</td>
					<td>
						<a class="btn btn-warning" href="#">Settings</a>
					</td>
					<td>
						<a class="btn btn-danger" href="#">Delete</a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection