<!DOCTYPE html>
<html>
	<head>
		<title>RAW Digiplay - @yield('title')</title>

		<meta lang="en">
		<link rel="stylesheet" type="text/css" href="/css/app.css">
	</head>
	<body>
		<div class="container">
			@yield('content')
		</div>
		<div class="row align-items-center bg-dark text-warning footer">
			<div class="col-sm-8">
				<h1 class="text-center">&copy;2018 Radio Warwick</h1>
			</div>
			<div class="col-sm-4">
				<div class="logo-sm">
					@include('layouts.logo')
				</div>
			</div>
		</div>
	</body>
</html>