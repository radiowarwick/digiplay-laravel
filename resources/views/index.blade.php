@extends('layouts.app')

@section('title', 'Home')

@section('breadcrumbs')
	{{ Breadcrumbs::render('index') }}
@endsection

@section('content')
	@include('forms.audio-search')
@endsection