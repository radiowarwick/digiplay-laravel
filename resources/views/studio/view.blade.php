<!DOCTYPE html>
<html lang="en">
	<head>
		<title>RAW Digiplay - Studio {{ $location }}</title>

		<meta name="viewport" content="width=device-width, initial-scale=1">	
		<meta charset="utf-8">

		<link rel="stylesheet" type="text/css" href="/css/app.css">
		
		<script src="/js/app.js"></script>
		<script src="/js/studio/main.js"></script>
	</head>
	<body class="text-white studio-body" style>
		<div class="container-fluid studio-now-next border-bottom border-warning border-3">
			<div class="row">
				<div class="col-sm-6">On now: RAW Jukebox</div>
				<div class="col-sm-6">On next: RAW Jukebox</div>
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
						<li class="nav-item">
							<a class="nav-link studio-tab bg-danger studio-reset">
								Reset
							</a>
						</li>
					</ul>
					<div class="tab-content studio-tab-content">
						<div class="tab-pane show active" id="music" role="tabpanel">
							Some Music here
						</div>
						<div class="tab-pane" id="messages" role="tabpanel">
							<div class="container-fluid studio-message-list border-bottom border-warning">
								<div class="row no-gutters studio-message-header border-bottom">
									<div class="col-sm-1"></div>
									<div class="col-sm-3">
										Sender
									</div>
									<div class="col-sm-5">
										Subject
									</div>
									<div class="col-sm-3">
										Date/Time
									</div>
								</div>
								@foreach($emails as $email)
									<div data-message-id="{{ $email->id }}" class="row no-gutters studio-message-row border-top">
										<div class="col-sm-1 text-warning">
											@if($email->new_flag == 't')
												<i class="fa fa-envelope"></i>
											@endif
										</div>
										<div class="col-sm-3 text-truncate">
											{{ preg_replace('/<.*>/', '', $email->sender) }}
										</div>
										<div class="col-sm-5 text-truncate">
											{{ $email->subject }}
										</div>
										<div class="col-sm-3">
											{{ date('d/m/y H:i', $email->datetime) }}
										</div>
									</div>
								@endforeach
							</div>
							<div class="container-fluid studio-message-container">
								<h3 class="text-truncate" id="studio-message-subject"></h3>
								<p id="studio-message-body">
								</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-5 studio-col-right">
					<h3>Plan - Default</h3>
				</div>
			</div>
		</div>

		<footer class="footer studio-footer bg-dark text-warning border-top border-warning border-3">
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-3">
						<div class="logo-sm">
							@include('layouts.logo')
						</div>
					</div>
					<div class="col-sm-7"></div>
					<div class="col-sm-2">
						<a href="{{ route('studio-logout', $key) }}" class="btn btn-lg btn-block btn-warning pull-right">Log Out</a>
					</div>
				</div>
			</div>
		</footer>
	</body>
</html>