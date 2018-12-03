@extends('layouts.app')

@section('title', 'User Search')

@section('breadcrumbs')
	{{ Breadcrumbs::render('admin-ldap') }}
@endsection

@section('content')
	<h1>User Edit/View - {{ $user->uid[0] }}</h1>

	<p>
		Edit and view the attributes for this member. Only change these values if you know what you are doing!
	</p>

	<form class="form-inline" action="{{ route('admin-ldap-view') }}">
		<input type="text" name="username" class="form-control mb-2 mr-2" value="{{ $user->uid[0] }}" placeholder="Username">
		<button class="btn btn-warning mb-2" type="submit">Search</button>
	</form>

	<table class="table table-stripped table-responsive">
		<thead>
			<tr>
				<th>Attribute</th>
				<th>Value</th>
			</tr>
		</thead>
		<tbody>
			@foreach($attributes as $attribute)
				<tr>
					<td>{{ $attribute }}</td>
					<td>
						<form class="form-inline" action="{{ route('admin-ldap-update') }}" method="POST">
							{{ csrf_field() }}
							<input type="hidden" value="{{ $user->uid[0] }}" name="username">
							<input type="hidden" value="{{ $attribute }}" name="key">
							<input type="text" class="form-control mb-2 mr-2" name="value" value="{{ $user->getAttribute($attribute)[0] }}">
							<button class="btn btn-warning mb-2" type="submit">Update</button>
						</form>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection