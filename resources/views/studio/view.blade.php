<!DOCTYPE html>
<html lang="en">
	<head>
		<title>RAW Digiplay - Studio {{ $location }}</title>

		<meta name="viewport" content="width=device-width, initial-scale=1">	
		<meta charset="utf-8">
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<link rel="stylesheet" type="text/css" href="/css/app.css">
		
		<script src="/js/app.js"></script>
		<script src="/js/studio/main.js"></script>
	</head>
	<body class="studio-body" style>
		<script type="text/javascript">
			const CENSOR_START = {{ $censor_start }};
			const CENSOR_END = {{ $censor_end }};
			const WEBSOCKET = "{{ env('WEBSOCKET') }}";
			const LOCATION = {{ $location }};
		</script>

		<div class="container-fluid studio-now-next border-bottom border-warning border-3">
			<div class="row">
				{{--
				<div class="col-sm-6">On now: RAW Jukebox</div>
				<div class="col-sm-6">On next: RAW Jukebox</div>
				--}}
			</div>
		</div>

		<div class="container-fluid studio-container">
			<div class="row no-gutters studio-container-row">
				<div class="col-sm-7 border-right border-warning border-3 studio-col-left">
					<ul class="nav nav-tabs nav-justified studio-tabs">
						<li class="nav-item">
							<a class="nav-link active studio-tab" data-toggle="tab" href="#music" role="tab">
								<i class="fa fa-music"></i>
								Music
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link studio-tab" data-toggle="tab" href="#messages" role="tab">
								<i class="fa fa-envelope"></i>
								Messages
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link studio-tab" data-toggle="tab" href="#playlists" role="tab">
								<i class="fa fa-th-list"></i>
								Playlists
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link studio-tab" data-toggle="tab" href="#log" role="tab">
								<i class="fa fa-pencil"></i>
								Log
							</a>
						</li>
						@if($location <= 2)
							<li class="nav-item">
								<a class="nav-link studio-tab bg-danger studio-reset" data-state="ready" data-placement="bottom" data-content="This action will restart the touchscreen and stop anything that it is playing! Click again if you want to do this.">
									<i class="fa fa-exclamation-triangle"></i>
									Reset
								</a>
							</li>
						@endif
					</ul>
					<div class="tab-content studio-tab-content">
						<div class="tab-pane show active" id="music" role="tabpanel">
							<div class="studio-song-search border-warning border-bottom">
								<div class="input-group">
									<input class="form-control" type="text" name="query" placeholder="Search...">
									<span class="input-group-btn">
										<button type="submit" class="btn btn-search btn-warning">
											Search
										</button>
									</span>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" id="studio-check-title" checked>
									<label class="form-check-label" for="studio-check-title">Title</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" id="studio-check-artist" checked>
									<label class="form-check-label" for="studio-check-artist">Artist</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" id="studio-check-album" checked>
									<label class="form-check-label" for="studio-check-album">Album</label>
								</div>
							</div>
							<div class="studio-song-search-results">
								<div class="studio-song-search-welcome">
									<h1>Hello {{ auth()->user()->name }}, welcome to Digiplay!</h1>
									<h2>To begin start loading songs from playlists, search or your showplan.</h2>
									<p>
										Remember to play all yellow adverts each hour! If you're not a specialist music show you must also play the following:
										<ul>
											<li>One A list song</li>
											<li>One B list song</li>
											<li>One A, B or C list song</li>
										</ul>
									</p>
								</div>
								<div class="studio-song-search-none" style="display:none;">
									<h2>No results found, please refine your search.</h2>
								</div>
								<div class="studio-song-search-table" style="display:none;">
									<table class="table table-hover">
										<thead>
											<tr>
												<th class="icon"></th>
												<th class="artist">Artist</th>
												<th class="title">Title</th>
												<th class="album">Album</th>
												<th class="length">Length</th>
											</tr>
										</thead>
										<tbody class="studio-song-search-table-results">
										</tbody>
									</table>
								</div>
								<div class="studio-song-search-loading text-center" style="display:none;">
									<h1>Searching...</h1>
									<h1><i class="fa fa-spinner fa-pulse"></i></h1>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="messages" role="tabpanel">
							<div class="container-fluid studio-message-list border-bottom border-warning">
								<table class="table table-hover">
									<thead>
										<tr>
											<th class="icon"></th>
											<th class="sender">Sender</th>
											<th class="subject">Subject</th>
											<th class="date">Date/Time</th>
										</tr>
									</thead>
									<tbody>
										@foreach($emails as $email)
											<tr data-message-id="{{ $email->id }}">
												<td class="icon">
													@if($email->new_flag == 't')
														<i class="fa fa-envelope"></i>
													@endif											
												</td>
												<td class="sender text-truncate">
													{{ preg_replace('/<.*>/', '', $email->sender) }}
												</td>
												<td class="subject text-truncate">
													{{ $email->subject }}
												</td>
												<td class="date text-truncate">
													{{ date('d/m/y H:i', $email->datetime) }}
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
							<div class="container-fluid studio-message-container">
								<h3 class="text-truncate border-bottom border-warning border-3" id="studio-message-subject"></h3>
								<p id="studio-message-body">
								</p>
							</div>
						</div>
						<div class="tab-pane" id="playlists" role="tabplanel">
							<div class="studio-playlist-container">
								@foreach($playlists as $playlist)
									<div class="card">
										<div class="card-header" data-toggle="collapse" href="#playlist-{{ $playlist->id }}">
											<div class="card-icon">
												<i class="fa fa-lg fa-arrow-circle-right"></i>
											</div>
											{{ $playlist->name }}
										</div>
										<div class="card-body studio-playlist-card collapse" id="playlist-{{ $playlist->id }}">
											<table class="table table-hover">
												<thead>
													<tr>
														<th class="icon"></th>
														<th class="artist">Artist</th>
														<th class="title">Title</th>
														<th class="album">Album</th>
														<th class="length">Length</th>
													</tr>
												</thead>
												<tbody>
													@foreach($playlist->audio as $audio)
														<tr data-audio-id="{{ $audio->id }}">
															@if($audio->censor == 't')
																<td class="icon censor">
																	<i class="fa fa-exclamation-circle"></i>
																</td>
															@else
																<td class="icon">
																	<i class="fa fa-music"></i>
																</td>
															@endif
															<td class="artist text-truncate">{{ $audio->artist->name }}</td>
															<td class="title text-truncate">{{ $audio->title }}</td>
															<td class="album text-truncate">{{ $audio->album->name }}</td>
															<td class="length text-truncate">{{ $audio->lengthString() }}</td>
														</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									</div>
								@endforeach
							</div>
						</div>
						<div class="tab-pane" id="log" role="tabplanel">
							<div class="container-fluid studio-log-add border-bottom border-warning">
								<div class="form-inline studio-log-form">
									<input type="text" class="form-control mr-sm-2 studio-log-artist" name="artist" class="studio-log-artist" placeholder="Artist">
									<input type="text" class="form-control mr-sm-2 studio-log-title" name="title" class="studio-log-title" placeholder="Title">
									<button type="button" class="btn btn-warning" name="submit-log">Log</button>
								</div>
							</div>
							<div class="container-fluid studio-log-table">
								<table class="table table-hover">
									<thead>
										<tr>
											<th class="artist">Artist</th>
											<th class="title">Title</th>
											<th class="date">Date/Time</th>
										</tr>
									</thead>
									<tbody>
										@foreach($log as $log_entry)
											<tr>
												<td class="artist text-truncate">{{ $log_entry->track_artist }}</td>
												<td class="title text-truncate">{{ $log_entry->track_title }}</td>
												<td class="date text-truncate">{{ date('d/m/y H:i', $log_entry->datetime) }}</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-5 studio-col-right">
					<div class="studio-showplan-header">
						<form class="col-sm-12 form-inline">
							<h2 class="mb-2 mr-sm-2">Plan</h2>
							@if(count($showplans) > 0)
								<div class="mb-2 mr-2">
									<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#showplan-modal">Select plan to load</button>
								</div>
							@else
								<h5 class="mb-2 mr-sm-2">No showplans to load</h5>
							@endif
							<div class="mb-2">
								<button type="button" class="btn btn-danger studio-clear-showplan" data-state="ready" data-placement="bottom" data-content="Are you sure you wish to clear plan? Click again to confirm">
									<i class="fa fa-trash fa-lg"></i>
								</button>
							</div>
						</form>
					</div>
					<div class="studio-showplan">
						@foreach($showplan->items as $item)
							<div class="studio-card card" data-item-id="{{ $item->id }}">
								<div class="card-body">
									@if($item->audio->censor == 't')
										<i class="censor fa fa-exclamation-circle"></i>
									@else
										<i class="fa fa-music"></i>
									@endif
									{{ $item->audio->artist->name }} - {{ $item->audio->title }}
									<div class="pull-right">
										{{ $item->audio->lengthString() }}
										<span class="studio-card-remove">
											<i class="fa fa-times-circle fa-lg"></i>
										</span>
									</div>
								</div>
							</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>

		<footer class="footer studio-footer bg-dark text-warning border-top border-warning border-3">
			<div class="container-fluid">
				<div class="row">
					<div class="logo-sm">
							@include('layouts.logo')
					</div>
					<div class="studio-time">
						<h2>12:51:00 AM</h2>
						<h5>Thursday 6th January</h5>
					</div>
					<div class="col-sm-2">
						<a href="{{ route('studio-logout', $key) }}" class="btn btn-lg btn-block btn-warning pull-right">Log Out</a>
					</div>
				</div>
			</div>
		</footer>

		<div class="modal fade" id="showplan-modal">
			<div class="modal-dialog">
				<div class="modal-content bg-dark text-white">
					<div class="modal-header">
						<h5>Showplans</h5>
						<button type="button" class="close text-warning" data-dismiss="modal">
							<i class="fa fa-times-circle"></i>
						</button>
					</div>
					<div class="modal-body">
						<p>
							Click one of your showplans to load it
						</p>
						<div class="list-group">
							@foreach($showplans as $showplan_iteration)
								@if($showplan_iteration->id > 4)
									<a class="list-group-item" href="{{ route('studio-load-plan', [$key, $showplan_iteration->id]) }}">
										{{ $showplan_iteration->name }}
									</a>
								@endif
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>