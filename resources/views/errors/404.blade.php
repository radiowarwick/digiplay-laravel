@extends('layouts.box')

@section('title', 'Error 404')

@Section('header', 'Error 404')

@section('content')
	<p>
		The page you was looking for was not found! Perhaps it got lost somewhere...
	</p>
	<p>
		<a href="/" class="btn btn-warning btn-block">Take Me Home</a>
	</p>
	<p>
		<a href="https://www.youtube.com/watch?v=BwyaqDVzPxM" target="_blank">
			<img class="img-fluid" src="/images/404.gif">
		</a>
	</p>
@endsection