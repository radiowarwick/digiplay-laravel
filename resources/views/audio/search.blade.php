@extends('layouts.app')

@section('title', 'Audio Search')

@section('breadcrumbs')
	{{ Breadcrumbs::render('audio-search') }}
@endsection

@section('content')
	@section('q', $q)

	@include('forms.audio-search')

	<h3>You got {{ $total }} results</h3>
  
  @if($total>0)
    <table class="table table-striped" cellspacing="0">
      <thead>
	  		<th class="title">Title</th>
	  		<th class="artist">Artist</th>
	  		<th class="album">Album</th>
	  		<th class="length">Length</th>
        @foreach($results as $r)
          <tr>
            <td class="title"> {{ $r->title }} </td>
            <td class="artist"> {{ $r->artist->name }} </td>
            <td class="album"> {{ $r->album->name }} </td>
            <td class="length">  2m </td>
           </td>
          </tr>
        @endforeach
      </thead>
    </table>
  @endif

	{{ $results->appends(['q' => $q])->links() }}
@endsection
