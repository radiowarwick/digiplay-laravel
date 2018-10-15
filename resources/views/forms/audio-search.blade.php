<script src="/js/search/search.js"></script>

<p>
	<div class="row">
		<div class="col-sm-10">
			<form method="GET" action="{{ route('audio-search') }}">
				<div class="input-group">
					<input class="form-control form-control-lg" type="text" name="q" value="@yield('q')" placeholder="Search here">
					<span class="input-group-btn">
						<button type="submit" class="btn btn-lg btn-search btn-warning" id="searchbutton">
							Search
						</button>
					</span>
				</div>
			</form>
		</div>
		<div class="col-sm-2">
			<button type="button" id="btn-search-options" class="btn btn-lg btn-block btn-warning">
				Options
			</button>
			<div id="search-options-template" class="d-none">
				<div class="row">
					<div class="custom-control-inline">
						<input type="checkbox" id="title" value="title" name="options[]"  {{ (empty($options) or in_array("title", $options)) ? "checked" : "" }}>
						 Title
					</div>
					<div class="custom-control-inline">
						<input type="checkbox" id="artist" value="artist" name="options[]"  {{ (empty($options) or in_array("artist", $options)) ? "checked" : "" }}>
						Artist
					</div>
					<div class="custom-control-inline">
						<input type="checkbox" id="album" value="album"  name="options[]"  {{ (empty($options) or in_array("album", $options)) ? "checked" : "" }}>
						Album
					</div>
				</div>

				<div class="row">
					<div class="custom-control-inline" id="types">
						<input type="checkbox" id="Music" value="Music" name="types[]"  {{ (empty($types) or in_array("Music", $types)) ? "checked" : "" }}>
						Music
					</div>
					<div class="custom-control-inline" id="types">
						<input type="checkbox" id="Jingle" value="Jingle" name="types[]"  {{ (!empty($types) and in_array("Jingle", $types)) ? "checked" : "" }}>
						Jingle
					</div>
					<div class="custom-control-inline" id="types">
						<input type="checkbox" id="Advert" value="Advert" name="types[]"  {{ (!empty($types) and in_array("Advert", $types)) ? "checked" : "" }}>
						Advert
					</div>
					@if(auth()->user()->hasPermission('Sustainer admin'))
						<div class="custom-control-inline" id="types">
							<input type="checkbox" id="Prerec" value="Prerec" name="types[]"   {{ (!empty($types) and in_array("Prerec", $types)) ? "checked" : "" }}>
							Prerecord
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</p>
