@extends('layouts.box')

@section('title', 'Login')

@section('header', 'Digiplay')

@section('content')
	@if(Session::has('status'))
		<h4 class="text-warning text-center">{{ Session::get('status') }}</h4>
	@endif
	<form method="POST" action="{{ route('login-post') }}">
		{{ csrf_field() }}
		<div class="form-group">
			<div class="input-group mb-2">
				<div class="input-group-prepend">
					<div class="input-group-text">
						<i class="fa fa-user" aria-hidden="true"></i>
					</div>
				</div>
				<input type="text" class="form-control" id="username" name="username" placeholder="Username">
			</div>
		</div>
		<div class="form-group">
			<div class="input-group mb-2">
				<div class="input-group-prepend">
					<div class="input-group-text">
						<i class="fa fa-lock" aria-hidden="true"></i>
					</div>
				</div>
				<input type="password" class="form-control" id="password" name="password" placeholder="Password">
			</div>
		</div>
		<div class="form-group">
			<button class="btn btn-warning text-white" type="submit">Login</button>
		</div>
	</form>
	@if($errors->any())
		@foreach ($errors->all() as $error)
			<p class="text-danger">{{ $error }}</p>
		@endforeach
	@endif
	<p>
		If you do not have a RAW account, get membership <a href="https://www.warwicksu.com/societies/raw/" target="_blank">get it here</a>.
	</p>

	<p>
		Forgotten your password? <a href="https://space.radio.warwick.ac.uk/space/passreset/" target="_blank">Click here!
	</a>
@endsection