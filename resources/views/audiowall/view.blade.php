@extends('layouts.app')

@section('title', 'Audiowall - ' . $set->name)

@section('breadcrumbs')
	{{ Breadcrumbs::render('audiowall-view', $set) }}
@endsection

@section('content')
	<div class="row">
		<div class="col-lg-9">
			<h1 class="text-truncate">
				{{ $set->name }}
			</h1>
		</div>
		<div class="col-lg-3">
			@if($set->hasAdmin(Auth::user()))
				<a class="btn btn-warning btn-lg pull-right" href="{{ route('audiowall-settings', $set) }}">Settings</a>
			@endif
			@if($set->hasEdit(Auth::user()))
				<button class="btn btn-success btn-lg btn-space pull-right audiowall-save">Save</button>
			@endif
		</div>
	</div>

	@if($set->hasEdit(Auth::user()))
		<p>
			<form class="audiowall-search">
				{{ csrf_field() }}
				<div class="input-group">
					<input class="form-control form-control-lg audiowall-search-input" type="text" placeholder="Search by artist/track/album name">
					<span class="input-group-btn">
						<button type="submit" class="btn btn-lg btn-search btn-warning">
							Search
						</button>
					</span>
				</div>
			</form>
		</p>
	@endif
	
	<div class="row">
		<div class="col-lg-4">
			<div class="list-group audiowall-list-group">
				@foreach($set->walls as $wall)
					<div class="list-group-item {{ ($wall->page == 0) ? "active" : "" }}" data-wall-page="{{ $wall->page }}">
						<div class="row no-gutters">
							<div class="col-lg-7 text-truncate audiowall-wall-name">
								{{ $wall->name }}
							</div>
							<div class="col-lg-5">
								<span class="badge badge-dark badge-pill audiowall-move-down"><i class="fa fa-arrow-down"></i></span>
								<span class="badge badge-dark badge-pill audiowall-move-up"><i class="fa fa-arrow-up"></i></span>
								<span class="badge badge-dark badge-pill audiowall-edit"><i class="fa fa-pencil"></i></span>
								<span class="badge badge-dark badge-pill audiowall-remove" data-content="Click again to delete page" data-state="ready"><i class="fa fa-times"></i></span>
							</div>
						</div>
					</div>
				@endforeach

				<div class="list-group-item audiowall-add-yes" {!! ($set->walls()->count() >= 8) ? "style=\"display:none;\"" : "" !!}>Add new page</div>
				<div class="list-group-item audiowall-add-no" {!! ($set->walls()->count() < 8) ? "style=\"display:none;\"" : "" !!}>Page limit reached</div>
				<div class="list-group-item form-inline audiowall-add-row" style="display:none;">
					<div class="form-row no-gutters">
						<div class="col-lg-7">
							<input type="text" class="form-control form-control-sm">
						</div>
						<div class="col-lg-5">
							<button class="btn btn-sm btn-warning audiowall-add-add">Add</button>
							<button class="btn btn-sm btn-danger audiowall-add-cancel">Cancel</button>
						</div>
					</div>
				</div>
			</div>
			@if($set->hasEdit(Auth::user()))
				<div class="row">
					<div class="audiowall-trash bg-danger">
						<div class="row">
							<div class="col-sm">
								Trash
							</div>
						</div>
						<div class="row">
							<div class="col-sm">
								<i class="fa fa-2x fa-trash"></i>
							</div>
						</div>
					</div>
				</div>
			@endif
		</div>
		<div class="col-lg-8 audiowall-wall-container">
			@foreach($set->walls as $wall)
				<div class="row wall-page" data-wall-page="{{ $wall->page }}" {!! ($wall->page > 0) ? "style=\"display:none;\"" : "" !!}>
					@for($i = 0; $i < 12; $i++)
						@php
							$item = $wall->items->where('item', $i)->first();

							$fg_colour = 'ffffff';
							$bg_colour = '428BCA';
							if($item != null) {
								foreach($item->colours as $colour) {
									$value = dechex($colour->value);
									while(strlen($value) < 6) {
										$value = "0" . $value;
									}

									if($colour->name == 'ForeColourRGB')
										$fg_colour = $value;
									else if($colour->name == 'BackColourRGB')
										$bg_colour = $value;
								}
							}
						@endphp

						<div class="audiowall-item" data-bg="{{ $bg_colour }}" data-fg="{{ $fg_colour }}"  style="color:#{{ $fg_colour }};background:#{{ $bg_colour }}" data-wall-item="{{ $i }}" data-wall-audio-id="{{ ($item == null) ? "" : $item->audio_id }}" data-item-length="{{ ($item == null) ? "" : $item->audio->length() }}" data-item-length-string="{{ ($item == null) ? "" : $item->audio->length_string() }}">
							<div class="row no-gutters">
								<div class="col-3">
									<i class="audiowall-settings fa fa-gear fa-lg audiowall-action-box" {!! ($item == null) ? "style=\"display:none;\"" : "" !!}></i>
								</div>
								<div class="col-6">
																	<div class="audiowall-time" {!! ($item == null) ? "style=\"display:none;\"" : "" !!}>
									<div class="audiowall-time-text">
											{{ ($item != null) ? $item->audio->length_string() : '' }}
									</div>
									<div class="audiowall-time-play">
										<i class="fa fa-play"></i>
									</div>
								</div>
								<div class="audiowall-add-btn audiowall-move" style="display:none;">
									<div class="audiowall-time-add">
										Add
									</div>
									<div class="audiowall-time-plus">
										<i class="fa fa-plus"></i>
									</div>
								</div>
								</div>
								<div class="col-3">
									<i class="audiowall-move audiowall-move-only fa fa-exchange fa-lg audiowall-action-box pull-right"></i>
								</div>
							</div>
							<div class="row audiowall-title no-gutters">
								<div class="col-sm audiowall-item-title-text">
									@if($item != null)
										{{ $item->text }}
									@endif
								</div>
							</div>
							<div class="row">

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

		<div class="audiowall-item audiowall-item-template" data-bg="428bca" data-fg="ffffff" data-wall-item data-wall-audio-id style="background:#428bca;color:#ffffff">
			<div class="row no-gutters">
				<div class="col-3">
					<i class="audiowall-settings fa fa-gear fa-lg audiowall-action-box" style="visibility: hidden;"></i>
				</div>
				<div class="col-6">
					<div class="audiowall-time" style="display:none;">
						<div class="audiowall-time-text">
						</div>
						<div class="audiowall-time-play">
							<i class="fa fa-play"></i>
						</div>
					</div>
				</div>
				<div class="col-3">
					<i class="audiowall-move fa fa-exchange fa-lg audiowall-action-box pull-right"></i>
				</div>
			</div>
			<div class="row audiowall-title no-gutters">
				<div class="col-sm audiowall-item-title-text">
				</div>
			</div>
		</div>

		<div class="list-group-item form-inline edit-row" style="display:none;">
			<div class="form-row no-gutters">
				<div class="col-lg-7">
					<input type="text" class="form-control form-control-sm audiowall-edit-input">
				</div>
				<div class="col-lg-5">
					<button class="btn btn-sm btn-warning audiowall-edit-save">Save</button>
					<button class="btn btn-sm btn-danger audiowall-edit-cancel">Cancel</button>
				</div>
			</div>
		</div>

		<div class="list-group-item audiowall-add-template" style="display:none;">
			<div class="row no-gutters">
				<div class="col-lg-7 text-truncate audiowall-wall-name">
				</div>
				<div class="col-lg-5">
					<span class="badge badge-dark badge-pill audiowall-move-down"><i class="fa fa-arrow-down"></i></span>
					<span class="badge badge-dark badge-pill audiowall-move-up"><i class="fa fa-arrow-up"></i></span>
					<span class="badge badge-dark badge-pill audiowall-edit"><i class="fa fa-pencil"></i></span>
					<span class="badge badge-dark badge-pill audiowall-remove" data-content="Click again to delete page" data-state="ready"><i class="fa fa-times"></i></span>
				</div>
			</div>
		</div>

		<div class="audiowall-search-results modal fade" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content bg-dark">
					<div class="modal-header">
						<h5 class="modal-title">Audio Search Results</h5>
						<button type="button" class="close text-warning" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body audiowall-search-results-container">
					</div>
				</div>
			</div>
		</div>

		<div class="audiowall-item-settings modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content bg-dark">
					<div class="modal-header">
						<h5 class="modal-title">Item Settings</h5>
						<button type="button" class="close text-warning" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<h3>Name</h3>
						<input type="text" class="form-control audiowall-item-name">

						<h3>Colour</h3>

						<div class="row">
							<div class="audiowall-colour-option-container">
								<div class="audiowall-colour-option" style="background:#FEF200" data-background="FEF200"></div>
								<div class="audiowall-colour-option" style="background:#F2881A" data-background="F2881A"></div>
								<div class="audiowall-colour-option" style="background:#F12429" data-background="F12429"></div>
							</div>
						</div>
						<div class="row">
							<div class="audiowall-colour-option-container">
								<div class="audiowall-colour-option" style="background:#00C05D" data-background="00C05D"></div>
								<div class="audiowall-colour-option" style="background:#52B944" data-background="52B944"></div>
								<div class="audiowall-colour-option" style="background:#02A650" data-background="02A650"></div>
							</div>
						</div>
						<div class="row">
							<div class="audiowall-colour-option-container">
								<div class="audiowall-colour-option" style="background:#F1A9FA" data-background="F1A9FA"></div>
								<div class="audiowall-colour-option" style="background:#B22494" data-background="B22494"></div>
								<div class="audiowall-colour-option" style="background:#703295" data-background="703295"></div>
							</div>
						</div>
						<div class="row">
							<div class="audiowall-colour-option-container">
								<div class="audiowall-colour-option" style="background:#99BDDD" data-background="99BDDD"></div>
								<div class="audiowall-colour-option" style="background:#428BCA" data-background="428BCA"></div>
								<div class="audiowall-colour-option" style="background:#005CAA" data-background="005CAA"></div>
							</div>
						</div>
						<div class="row">
							<btn class="audiowall-item-settings-colour-btn btn btn-warning btn-lg m-auto">Custom</btn>
							<input type="color" class="audiowall-item-settings-colour d-none">
						</div>

						<div class="audiowall-edit-example">
							Example
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-success audiowall-item-save">Save</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			</div>
		</div>

		<div class="audiowall-item audiowall-item-search" data-bg="428bca" data-fg="ffffff" data-wall-item data-wall-audio-id data-item-length-string data-item-length style="background:#428bca;color:#ffffff;display:none;">
			<div class="row">
				<div class="audiowall-time">
					<div class="audiowall-time-text">
					</div>
					<div class="audiowall-time-play">
						<i class="fa fa-play"></i>
					</div>
				</div>
				<div class="audiowall-time audiowall-search-add">
					<div class="audiowall-time-add">
						Add
					</div>
					<div class="audiowall-time-plus">
						<i class="fa fa-plus"></i>
					</div>
				</div>
			</div>
			<div class="row audiowall-title no-gutters">
				<div class="col-sm audiowall-item-title-text">
				</div>
			</div>
		</div>
	@endif
@endsection