<script src="/js/audio/playlist_change.js"></script>

<div class="modal fade playlist-modal">
	<div class="modal-dialog">
		<div class="modal-content bg-dark">
			<div class="modal-header">
				<h5>Playlists</h5>
				<button type="button" class="close text-warning" data-dismiss="modal">
					<i class="fa fa-times-circle"></i>
				</button>
			</div>
			<div class="modal-body">
				<div class="list-group">
				@foreach(\App\Playlist::orderBy('sustainer')->orderBy('sortorder')->get() as $playlist)
					<div class="list-group-item playlist-item" data-playlist-id="{{ $playlist->id }}">
						{{ $playlist->name }}
					</div>
				@endforeach
				</div>
			</div>
		</div>
	</div>
</div>