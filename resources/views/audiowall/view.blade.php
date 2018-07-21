@extends('layouts.app')

@section('title', 'Audiowall - ' . $set->name)

@section('breadcrumbs')
	{{ Breadcrumbs::render('audiowall-view', $set) }}
@endsection

@section('content')
	<h1>{{ $set->name }}</h1>
	
	<div class="row">
		<div class="list-group col-sm-3">
			@foreach($set->walls as $wall)
				<div class="list-group-item">{{ $wall->name }}</div>
			@endforeach
		</div>
		<div class="col-sm-9">
			@foreach($set->walls as $wall)
				<div class="row" data-wall-page="{{ $wall->page }}">
					@for($i = 0; $i < 12; $i++)
						<div class="col-sm-4" data-wall-item="">
							@if(($item = $wall->items->where('item', $i)->first()) != null)
								{{ $item->text }}
							@endif
						</div>
					@endfor
				</div>
				<hr>
			@endforeach
		</div>
	</div>
@endsection