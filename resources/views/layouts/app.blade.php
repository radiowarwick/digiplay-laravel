<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-4483020-7"></script>
		<script>
		 	window.dataLayer = window.dataLayer || [];
		 	function gtag(){dataLayer.push(arguments);}
		 	gtag('js', new Date());

		 	gtag('config', 'UA-4483020-7');
		</script>

		<title>RAW Digiplay - @yield('title')</title>

		<meta name="viewport" content="width=device-width, initial-scale=1">	
		<meta charset="utf-8">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<meta name="theme-color" content="#2b2b2b">

		<link rel="stylesheet" type="text/css" href="/css/app.css">
		<link rel="shortcut icon" href="/img/favicon.ico">
		
		<script src="/js/app.js"></script>
	</head>
	<body class="text-white">
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-warning border-bottom border-3">
			<a class="navbar-brand wave-sm" href="/">@include('layouts.logo')</a>
			
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item {{ Request::segment(1) == '' ? 'active' : '' }}">
						<a class="nav-link" href="{{ route('index') }}">Home</a>
					</li>
					<li class="nav-item {{ Request::segment(1) == 'audio' ? 'active' : '' }}">
						<a class="nav-link" href="{{ route('audio-index') }}">Audio Library</a>
					</li>
					<li class="nav-item {{ Request::segment(1) == 'audiowall' ? 'active' : '' }}">
						<a class="nav-link" href="{{ route('audiowall-index') }}">Audiowalls</a>
					</li>
					<li class="nav-item {{ Request::segment(1) == 'showplan' ? 'active' : '' }}">
						<a class="nav-link" href="{{ route('showplan-index') }}">Showplans</a>
					</li>
					@if(auth()->user()->hasPermission('Can view admin page'))
						<li class="nav-item {{ Request::segment(1) == 'admin' ? 'active' : '' }}">
							<a class="nav-link" href="{{ route('admin-index') }}">Admin</a>
						</li>
					@endif
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<form action="{{ route('audio-search') }}" class="form-inline mr-sm-2">
				    	<input class="form-control mr-sm-2" name="q" type="text" placeholder="Search audio">
				    	<button class="btn btn-warning" type="submit">Search</button>
				    </form>

					<li class="nav-item dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							{{ Auth::user()->name }}
							<b class="caret"></b>
						</a>
						<div class="dropdown-menu dropdown-menu-right nav-user-dropdown">
							{{-- <a class="dropdown-item" href="/profile/{{ Auth::user()->username }}"><i class="fa fa-fw fa-user"></i> Profile</a> --}}
							<a class="dropdown-item" href="/logout"><i class="fa fa-fw fa-sign-out"></i> Logout</a>
						</div>
					</li>
				</ul>
			</div>
		</nav>

		@yield('breadcrumbs')
		
		<div class="container">
			<p>{{-- Spacer --}}</p>
			@if(isset($messages))
				@foreach($messages as $message)
					<div class="alert alert-warning alert-dismissible fade show" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						{{ $message }}
					</div>
				@endforeach
			@endif

			@yield('content')
		</div>

		<footer class="footer bg-dark text-warning border-top border-warning border-3">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-sm-6 align-self-center">
						<h3 class="text-center">&copy; {{ date('Y') }} Radio Warwick</h3>
					</div>
					<div class="col-sm-4 align-self-center">
						<div class="logo-sm mx-auto">
							@include('layouts.logo')
						</div>
					</div>
				</div>
			</div>
		</footer>
	</body>
</html>