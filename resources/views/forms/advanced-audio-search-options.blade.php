<div id="advancedsearchform">

  <div class="grid">
    <div class="row">
      <div class="custom-control custom-checkbox custom-control-inline">
        <input type="checkbox" id="title" value="title" name="options[]" class="custom-control-input" @if(empty($options) || in_array("title",$options)) checked @endif>
        <label class="custom-control-label" for="title">Title</label>
      </div>
      <div class="custom-control custom-checkbox custom-control-inline">
        <input type="checkbox" id="artist" value="artist" name="options[]" class="custom-control-input" @if(empty($options) || in_array("artist",$options)) checked @endif>
        <label class="custom-control-label" for="artist">Artist</label>
      </div>
      <div class="custom-control custom-checkbox custom-control-inline">
        <input type="checkbox" id="album" value="album"  name="options[]" class="custom-control-input" @if(empty($options) || in_array("album",$options)) checked @endif>
        <label class="custom-control-label" for="album">Album</label>
      </div>
    </div>

  <div class="row">
      <div class="custom-control custom-checkbox custom-control-inline" id="types">
        <input type="checkbox" id="Music" value="Music" name="types[]" class="custom-control-input" @if(empty($types) || in_array("Music",$types)) checked @endif>
        <label class="custom-control-label" for="Music">Music</label>
      </div>
      <div class="custom-control custom-checkbox custom-control-inline" id="types">
        <input type="checkbox" id="Jingle" value="Jingle" name="types[]" class="custom-control-input" @if(!empty($types) && in_array("Jingle",$types)) checked @endif>
        <label class="custom-control-label" for="Jingle">Jingle</label>
      </div>
      <div class="custom-control custom-checkbox custom-control-inline" id="types">
        <input type="checkbox" id="Advert" value="Advert" name="types[]" class="custom-control-input" @if(!empty($types) && in_array("Advert",$types)) checked @endif>
        <label class="custom-control-label" for="Advert">Advert</label>
      </div>
      <div class="custom-control custom-checkbox custom-control-inline" id="types">
        <input type="checkbox" id="Prerec" value="Prerec" name="types[]" class="custom-control-input" @if(!empty($types) && in_array("Prerec",$types)) checked @endif>
        <label class="custom-control-label" for="Prerec">Prerec</label>
      </div>
    </div>

  </div>

</div>
