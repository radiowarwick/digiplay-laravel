<!DOCTYPE html>
<html>
	<head>
		<title>RAW Digiplay - @yield('title')</title>

		<link rel="stylesheet" type="text/css" href="/css/app.css">
	</head>
	<body>
		<div class="container">
			@yield('content')
		</div>
		<div class="row">
			<div class="col-sm-8">
				&copy;2018 Radio Warwick
			</div>
			<div class="col-sm-4">
				<div class="logo">
					@include('layouts.logo')
				</div>
			</div>
		</div>
	</body>
</html>