<div id="advancedsearchform">
  <div class="custom-control custom-checkbox custom-control-inline">
    <input type="checkbox" id="title" value="title" name="options[]" class="custom-control-input" @if(!empty($options) && in_array("title",$options)) {{"checked"}} @endif>
    <label class="custom-control-label" for="title">Track</label>
  </div>
  <div class="custom-control custom-checkbox custom-control-inline">
    <input type="checkbox" id="artist" value="artist" name="options[]" class="custom-control-input" @if(!empty($options) && in_array("artist",$options)) {{"checked"}} @endif>
    <label class="custom-control-label" for="artist">Artist</label>
  </div>
  <div class="custom-control custom-checkbox custom-control-inline">
    <input type="checkbox" id="album" value="album"  name="options[]" class="custom-control-input" @if(!empty($options) && in_array("album",$options)) {{"checked"}} @endif>
    <label class="custom-control-label" for="album">Album</label>
  </div>
</div>
