@extends('layouts.app')

@section('title', 'Audio Search')

@section('content')
	@section('q', $q)

	@include('forms.audio-search')

	<h3>You got {{ $total }} results</h3>

	<ul>
		@foreach($results as $r)
			<li>{{ $r->title }} by {{ $r->artist->first()->name }}</li>
		@endforeach
	</ul>

	{{ $results->appends(['q' => $q])->links() }}
@endsection