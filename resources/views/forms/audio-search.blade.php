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
  <button type="button" id="advancedsearchtoggle" class="btn btn-lg btn-warning"> Advanced Options</button>
  @include('forms.advanced-audio-search-options')
	</form>
</p>

