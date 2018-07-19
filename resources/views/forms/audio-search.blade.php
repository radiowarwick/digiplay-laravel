<script src="/js/advancedsearch.js"></script>
<p>
	<form method="GET" action="{{ route('audio-search') }}">
		<div class="input-group">
			<input class="form-control form-control-lg" type="text" name="q" value="@yield('q')" placeholder="Search by Artist/Track/Album Name">
			<span class="input-group-btn">
				<button type="submit" class="btn btn-lg btn-search btn-warning">
					Search
				</button>
			</span>
		</div>
	</form>
  <button id="advancedsearchtoggle" class="btn btn-lg btn-search btn-warning"> Advanced Options</button>
</p>

@include('forms.advanced-audio-search-options')
