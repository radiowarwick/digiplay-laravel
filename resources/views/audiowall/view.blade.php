@extends('layouts.app')

@section('title', 'Audiowall - ' . $set->name)

@section('breadcrumbs')
	{{ Breadcrumbs::render('audiowall-view', $set) }}
@endsection

@section('content')
	<div class="row">
		<div class="col-lg-10">
			<h1 class="text-truncate">
				{{ $set->name }}
			</h1>
		</div>
		<div class="col-lg-2">
			@if($set->hasAdmin(Auth::user()))
				<a class="btn btn-warning btn-lg pull-right" href="{{ route('audiowall-settings', $set) }}">Settings</a>
			@endif
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-4">
			<div class="list-group">
				@foreach($set->walls as $wall)
					<div class="list-group-item {{ ($wall->page == 0) ? "active" : "" }}" data-wall-page="{{ $wall->page }}">
						<div class="row no-gutters">
							<div class="col-lg-7 text-truncate">
								{{ $wall->name }}
							</div>
							<div class="col-lg-5">
								<span class="badge badge-dark badge-pill audiowall-remove" data-content="Click again to delete page" data-state="ready"><i class="fa fa-times"></i></span>
								<span class="badge badge-dark badge-pill audiowall-move-down"><i class="fa fa-arrow-down"></i></span>
								<span class="badge badge-dark badge-pill audiowall-move-up"><i class="fa fa-arrow-up"></i></span>
								<span class="badge badge-dark badge-pill"><i class="fa fa-pencil"></i></span>
							</div>
						</div>
					</div>
				@endforeach
			</div>
			<div class="row">
				<div class="audiowall-item" id="audiowall-trash">
					<div class="row audiowall-title no-gutters">
						<div class="col-sm">
							Trash
						</div>
					</div>
					<div class="row text-center">
						<i class="fa fa-2x fa-trash"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-8">
			@foreach($set->walls as $wall)
				<div class="row wall-page" data-wall-page="{{ $wall->page }}" {!! ($wall->page > 0) ? "style=\"display:none;\"" : "" !!}>
					@for($i = 0; $i < 12; $i++)
						@php($item = $wall->items->where('item', $i)->first())

						<div class="audiowall-item" data-wall-item="{{ $i }}" data-wall-audio-id="{{ ($item == null) ? "" : $item->audio_id }}">
							<div class="row no-gutters">
								<div class="col-6 text-left">
									<i class="fa fa-gear fa-lg text-left"></i>
								</div>
								<div class="col-6 text-right">
									<i class="fa fa-arrows fa-lg text-right"></i>
								</div>
							</div>
							<div class="row audiowall-title no-gutters">
								<div class="col-sm">
									@if($item != null)
										{{ $item->text }}
									@endif
								</div>
							</div>
							<div class="row">
								<div class="audiowall-time">
									<div class="audiowall-time-text">
										1m 56s
									</div>
									<div class="audiowall-time-play">
										<i class="fa fa-play"></i>
									</div>
								</div>
							</div>
						</div>
					@endfor
				</div>
			@endforeach
		</div>
	</div>

	<script src="/js/audiowall/view.js"></script>

	@if($set->hasEdit(Auth::user()))
		<script src="/js/audiowall/edit.js"></script>
	@endif
@endsection