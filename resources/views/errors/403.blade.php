@extends('layouts.box')

@section('title', 'Error 403')

@Section('header', 'Error 403')

@section('content')
	<p>
		You're not allowed to view this page, sorry!
	</p>
	<p>
		<a href="/" class="btn btn-warning btn-block">Take Me Home</a>
	</p>
@endsection