@extends('layouts.app')

@section('title', 'Users')

@section('content')
	@foreach($users as $user)
		<li>{{ $user->username }}</li>
	@endforeach
	</ul>
@endsection