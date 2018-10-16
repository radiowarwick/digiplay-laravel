<script src="/js/search/search.js"></script>

<p>
	<form method="GET" action="{{ route('audio-search') }}">
		<div class="row">
			<div class="col-sm-10">
					<div class="input-group">
						<input class="form-control form-control-lg" type="text" name="q" value="@yield('q')" placeholder="Search here">
						<span class="input-group-btn">
							<button type="submit" class="btn btn-lg btn-search btn-warning" id="searchbutton">
								Search
							</button>
						</span>
					</div>
			</div>
			<div class="col-sm-2">
				<button type="button" id="btn-search-options" class="btn btn-lg btn-block btn-warning" data-toggle="collapse" href="#audio-search-options">
					Options
				</button>
			</div>
		</div>


		<div class="card collapse" id="audio-search-options">
			<div class="card-body">
				<div class="form-group row">
					<div class="col-sm-2">Attributes</div>
					<div class="col-sm-10">
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" id="title" value="title" name="options[]"  {{ (empty($options) or in_array('title', $options)) ? 'checked' : '' }}>
							<label class="form-check-label" for="title">
								Title
							</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" id="artist" value="artist" name="options[]"  {{ (empty($options) or in_array('artist', $options)) ? 'checked' : '' }}>
							<label class="form-check-label" for="artist">
								Artist
							</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" id="album" value="album"  name="options[]"  {{ (empty($options) or in_array('album', $options)) ? 'checked' : '' }}>
							<label class="form-check-label" for="album">
								Album
							</label>
						</div>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-sm-2">Types</div>
					<div class="col-sm-10">
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" id="Music" value="Music" name="types[]"  {{ (empty($types) or in_array('Music', $types)) ? 'checked' : '' }}>
							<label class="form-check-label" for="Music">
								Music
							</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" id="Jingle" value="Jingle" name="types[]"  {{ (!empty($types) and in_array('Jingle', $types)) ? 'checked' : '' }}>
							<label class="form-check-label" for="Jingle">
								Jingle
							</label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" id="Advert" value="Advert" name="types[]"  {{ (!empty($types) and in_array('Advert', $types)) ? 'checked' : '' }}>
							<label class="form-check-label" for="Advert">
								Advert
							</label>
						</div>
						@if(auth()->user()->hasPermission('Sustainer admin'))
							<div class="form-check form-check-inline">
								<input type="checkbox" class="form-check-input" id="Prerec" value="Prerec" name="types[]"   {{ (!empty($types) and in_array('Prerec', $types)) ? 'checked' : '' }}>
								<label class="form-check-label" for="Prerec">
									Prerecord
								</label>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</form>
</p>
