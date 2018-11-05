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

	@if(auth()->user()->hasPermission('Can view studio logins'))
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

	@if(auth()->user()->hasPermission('Sustainer admin'))
		<div class="card" style="width: 18rem; display: inline-table;">
			<div class="card-body">
				<h5 class="card-title">Sustainer Management</h5>
				<p>
					Manage the prerecorded shows and playlists for the sustainer service
				</p>
				<a href="{{ route('admin-sustainer-index') }}" class="btn btn-warning">View</a>
			</div>
		</div>
	@endif

	@if(auth()->user()->hasPermission('Can view studio keys'))
		<div class="card" style="width: 18rem; display: inline-table;">
			<div class="card-body">
				<h5 class="card-title">Studio Keys</h5>

				<p>
					Only view a studio if you have a good reason to! Otherwise this can cause problems for members using them!
				</p>

				<table class="table table-sm">
					<thead>
						<tr>
							<th>Location</th>
							<th>Key</th>
							<th>View</th>
						</tr>
					</thead>
					<tbody>
						@foreach(\App\Config::where('parameter', 'security_key')->where('location', '>', 0)->orderBy('location')->get() as $config)
							<tr>
								<td>{{ $config->location }}</td>
								<td>{{ $config->val }}</td>
								<td>
									<a target="_blank" href="{{ route('studio-view', $config->val) }}" class="btn btn-warning btn-sm">
										View
									</a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	@endif
@endsection