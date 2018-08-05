@extends('layouts.app')

@section('title', 'Audio Search')

@section('breadcrumbs')
	{{ Breadcrumbs::render('audio-search') }}
@endsection

@section('content')
	@section('q', $q)

	@include('forms.audio-search')

  <h3>
    @if(empty(request()->get('options')))
      You did not select to search by at least one of title, album or artist. You can select these under Advanced Options.
    @else
      Search term needed or term is too short
    @endif
</h3>
	
@endsection
