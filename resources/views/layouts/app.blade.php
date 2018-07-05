<!DOCTYPE html>
<html>
	<head>
		<title>RAW Digiplay - @yield('title')</title>

		<meta lang="en">
		<meta name="viewport" content="width=device-width">
		<link rel="stylesheet" type="text/css" href="/css/app.css">
	</head>
	<body>
		<div class="container">
			<h1>{{ Auth::user()->name }} - <a href="/logout">Logout</a></h1>
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