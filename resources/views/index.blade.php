@extends('layouts.app')

@section('title', 'Home')
@php($name = 'index')

@section('content')
	@include('forms.audio-search')
@endsection