@extends('layouts.app')

@section('title', 'Group Permissions')

@section('breadcrumbs')
	{{ Breadcrumbs::render('admin-group-permission', $group->id) }}
@endsection

@section('content')
	<h1>Group Permissions</h1>
	<h2>{{ $group->name }}</h2>

	<form action="{{ route('admin-group-permission-post', $group->id) }}" method="POST">
		{{ csrf_field() }}

		<table class="table table-lg">
			<thead>
				<tr>
					<th style="width:10%;">Enabled</th>
					<th>Name</th>
				</tr>
			</thead>
			<tbody>
				@foreach($permissions as $permission)
					<tr>
						<td>
							<input name="permissions[]" value="{{ $permission->id }}" type="checkbox" {{ $group->has_permission($permission->name) ? "checked" : "" }}>
						</td>
						<td>{{ $permission->name }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
		<button class="btn btn-large btn-warning">Save</button>
		<a href="{{ route('admin-group-index') }}" class="btn btn-large btn-danger">Cancel</a>
	</form>
@endsection