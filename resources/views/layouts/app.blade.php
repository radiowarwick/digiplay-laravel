<!DOCTYPE html>
<html>
	<head>
		<title>RAW Digiplay - @yield('title')</title>

		<meta lang="en">
		<meta name="viewport" content="width=device-width">
		<link rel="stylesheet" type="text/css" href="/css/app.css">
	</head>
	<body>
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
			<a class="navbar-brand wave-sm" href="/">@include('layouts.logo')</a>
			
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav mr-auto">
					<a class="nav-link" href="/">Home</a>
				</ul>
				<span class="navbar-text text-warning">
					{{ Auth::user()->name }}
				</span>
			</div>
		</nav>
		<div class="container">
			<h1>Some Awesome Content!</h1>
			@yield('content')
		</div>
		<div class="row align-items-center bg-dark text-warning footer">
			<div class="col-sm-8">
				<h3 class="text-center">&copy;2018 Radio Warwick</h3>
			</div>
			<div class="col-sm-4">
				<div class="logo-sm">
					@include('layouts.logo')
				</div>
			</div>
		</div>
	</body>
</html>