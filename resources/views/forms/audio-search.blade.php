<script src="/js/advancedsearch.js"></script>
<p>
	<form method="GET" action="{{ route('audio-search') }}">
		<div class="input-group">
			<input class="form-control form-control-lg" type="text" name="q" value="@yield('q')" placeholder="Search here">
			<span class="input-group-btn">
				<button type="submit" class="btn btn-lg btn-search btn-warning" id="searchbutton">
					Search
				</button>
			</span>
		</div>
  <div class="container">
    <div class="row">
      <div class="col">
        <button type="button" id="advancedsearchtoggle" class="btn btn-lg btn-warning"> Advanced Options</button>
      </div>
      <div class="col">
        @include('forms.advanced-audio-search-options')
      </div>
    </div>
  </div>
	</form>
</p>

