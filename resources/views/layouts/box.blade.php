<!DOCTYPE html>
<html>
	<head>
		<title>RAW Digiplay - @yield('title')</title>

		<meta lang="en">
		<meta name="viewport" content="width=device-width">
		<link rel="stylesheet" type="text/css" href="/css/app.css">
	</head>
	<body class="bg-light">
		<div class="container">
			<div class="row">
				<div class="box-absolute-center bg-dark text-white">
					<div class="logo-box">
						@include('layouts.logo')
					</div>

					@if(View::hasSection('header'))
						<h2 class="text-white text-center">@yield('header')</h2>
					@endif

					@yield('content')
				</div>
			</div>
		</div>
	</body>
</html>