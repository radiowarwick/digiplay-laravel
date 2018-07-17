@extends('layouts.app')

@section('title', 'Admin')

@section('breadcrumbs')
	{{ Breadcrumbs::render('admin-index') }}
@endsection

@section('content')
	<h1>Admin</h1>

	@if(auth()->user()->hasPermission('Can edit groups'))
		<div class="card" style="width: 18rem;">
			<div class="card-body">
				<h5 class="card-title">Group Management</h5>
				<p>
					Manage user groups, group membership and group permissions
				</p>
				<a href="{{ route('admin-group-index') }}" class="btn btn-warning">Manage</a>
			</div>
		</div>
	@endif
@endsection