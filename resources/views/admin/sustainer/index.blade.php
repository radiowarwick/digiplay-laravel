@extends('layouts.app')

@section('title', 'Sustainer Admin')

@section('breadcrumbs')
	{{ Breadcrumbs::render('admin-sustainer-index') }}
@endsection

@section('content')
	<script src="/js/sustainer/schedule.js"></script>
	{{ csrf_field() }}

	<h1>Sustainer</h1>

	<div class="row">
		<div class="col-md-3">
			<div class="list-group">
				@foreach($playlists as $playlist)
					<div class="list-group-item" style="background:#{{ $playlist->colour->colour }};color:#{{ $playlist->colour->foreground() }}">
						{{ $playlist->name }}
					</div>
				@endforeach
			</div>
		</div>
		<div class="col-md-9">
			<table class="table table-bordered bg-white text-dark">
				<thead>
					<tr>
						<th></th>
						<th>Monday</th>
						<th>Tuesday</th>
						<th>Wednesday</th>
						<th>Thursday</th>
						<th>Friday</th>
						<th>Saturday</th>
						<th>Sunday</th>
					</tr>
				</thead>
				<tbody>
					@for($hour = 0, $i = 0; $hour <= 23; $hour++)
						<tr>
							<td>{{ $hour < 10 ? '0' . $hour : $hour }}:00</td>
							@for($day = 1; $day <= 7; $day++, $i++)
								<td class="text-center slot" style="background:#{{ $slots[$i]->playlist->colour->colour }};" data-slot-id="{{ $slots[$i]->id }}" data-prerec-id="{{ $slots[$i]->audioid }}" data-playlist-id="{{ $slots[$i]->playlist->id }}" data-day="{{ $day }}" data-hour="{{ $hour }}">
									@if(!is_null($slots[$i]->audioid))
										<i class="fa fa-clock-o"></i>
									@endif
								</td>
							@endfor
						</tr>
					@endfor
				</tbody>
			</table>
		</div>
	</div>

	<div class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content bg-dark">
				<div class="modal-header">
					<h5>Slot - Wednesday 19:00</h5>
					<button type="button" class="close text-warning" data-dismiss="modal">
						<i class="fa fa-times-circle"></i>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="form-control-label">
							Playlist
						</label>
						<select id="modal-playlist" class="form-control">
							@foreach($playlists as $playlist)
								 <option value="{{ $playlist->id }}">
									{{ $playlist->name }}
								</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" id="modal-save" class="btn btn-success">Save</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
				</div>
			</div>
		</div>
	</div>
@endsection