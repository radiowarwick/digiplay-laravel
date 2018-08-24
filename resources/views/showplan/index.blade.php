@extends('layouts.app')

@section('title', 'Showplans')

@section('breadcrumbs')
	{{ Breadcrumbs::render('showplan-index') }}
@endsection

@section('content')
	<h1>Showplans</h1>

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