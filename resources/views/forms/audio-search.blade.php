<p>
	<form method="GET" action="{{ route('audio-search') }}">
		<div class="input-group">
			<input class="form-control form-control-lg" type="text" name="q" value="@yield('q')" placeholder="Artist/Track/Album Name">
			<span class="input-group-btn">
				<button type="submit" class="btn btn-lg btn-search btn-warning">
					Search
				</button>
			</span>
		</div>
	</form>
	<a href="/tracks/advanced">Advanced Search</a>
</p>