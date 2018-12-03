@extends('layouts.app')

@section('title', 'User Search')

@section('breadcrumbs')
	{{ Breadcrumbs::render('admin-ldap') }}
@endsection

@section('content')
	<h1>Username not found!</h1>

	<p>
		Could not find the username. Try searching again.
	</p>

	<form class="form-inline" action="{{ route('admin-ldap-view') }}">
		<input type="text" name="username" class="form-control mb-2 mr-2" placeholder="Username">
		<button class="btn btn-warning mb-2" type="submit">Search</button>
	</form>
@endsection