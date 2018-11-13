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
		<meta name="description" content="RAW Digiplay - Audio management system for the members of Radio Warwick at the University of Warwick.">

		<link rel="stylesheet" type="text/css" href="/css/app.css">
		<link rel="shortcut icon" href="/img/favicon.ico">

		<script src="/js/app.js"></script>
		<script type="text/javascript">
			@if((date('d') >= 15 and date('m') == 11) or date('m') == 12)
				json = '/js/particles-snow.json';
			@else
				json = '/js/particles.json';
			@endif

			window.particlesJS.load('particles-js', json, function() {
  				console.log('particles.js loaded - callback');
			});
		</script>
	</head>
	<body class="bg-dark body-box">
		<div class="container page-height d-flex justify-content-center align-items-center" id="particles-js">
				<div class="bg-dark text-white box-absolute-center col">
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