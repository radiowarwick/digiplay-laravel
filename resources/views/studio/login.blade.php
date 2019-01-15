@extends($location == 2 ? 'layouts.studio-two' : 'layouts.box')

@section('title', 'Login')

@section('header', 'Studio ' . $location)

@section('content')
	@if(Session::has('status'))
		<h4 class="text-warning text-center">{{ Session::get('status') }}</h4>
	@endif

	<a href="{{ route('studio-login', $key) }}" class="btn btn-block text-white bg-warwick">Login with Warwick ITS account</a>

	@if($errors->any())
		@foreach ($errors->all() as $error)
			<p class="text-warning">{{ $error }}</p>
		@endforeach
	@endif
@endsection
