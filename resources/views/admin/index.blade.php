@extends('layouts.app')

@section('title', 'Admin')

@section('breadcrumbs')
	{{ Breadcrumbs::render('admin-index') }}
@endsection

@section('content')
	<h1>Admin</h1>

	@if(auth()->user()->hasPermission('Can edit groups'))
		<div class="card" style="width: 18rem; display: inline-table;">
			<div class="card-body">
				<h5 class="card-title">Group Management</h5>
				<p>
					Manage user groups, group membership and group permissions
				</p>
				<a href="{{ route('admin-group-index') }}" class="btn btn-warning">Manage</a>
			</div>
		</div>
	@endif

	@if(auth()->user()->hasPermission('Can search studio logins'))
		<div class="card" style="width: 18rem; display: inline-table;">
			<div class="card-body">
				<h5 class="card-title">Studio Logins</h5>
				<p>
					Search for logins in each studio
				</p>
				<a href="{{ route('admin-studio-index') }}" class="btn btn-warning">View</a>
			</div>
		</div>
	@endif
@endsection