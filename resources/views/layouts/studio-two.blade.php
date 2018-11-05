<!DOCTYPE html>
<html lang="en">
	<head>
		<title>RAW Digiplay - @yield('title')</title>

		<meta name="viewport" content="width=device-width, initial-scale=1">	
		<meta charset="utf-8">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<meta name="theme-color" content="#2b2b2b">

		<link rel="stylesheet" type="text/css" href="/css/app.css">
		<link rel="shortcut icon" href="/img/favicon.ico">

		<script src="/js/app.js"></script>
	</head>
	<body class="bg-dark body-box">
		<div class="container">
				<div class="bg-dark text-white box-absolute-center">
					<div class="logo-box">
						@include('layouts.logo')
					</div>

					@if(View::hasSection('header'))
						<h2 class="text-white text-center">@yield('header')</h2>
					@endif

					@yield('content')
				</div>
		</div>
	</body>
</html>