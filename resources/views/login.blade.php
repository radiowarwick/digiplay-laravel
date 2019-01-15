@extends('layouts.box')

@section('title', 'Login')

@section('header', 'Digiplay')

@section('content')
	@if(Session::has('status'))
		<h4 class="text-warning text-center">{{ Session::get('status') }}</h4>
	@endif

	<a href="/oauth" class="btn btn-block text-white bg-warwick">Login with Warwick ITS account</a>

	@if($errors->any())
		@foreach ($errors->all() as $error)
			<p class="text-danger">{{ $error }}</p>
		@endforeach
	@endif
	<p>
		You need to be a RAW member to login, <a href="https://www.warwicksu.com/societies/raw/" target="_blank">get membership here</a>.
	</p>
@endsection
