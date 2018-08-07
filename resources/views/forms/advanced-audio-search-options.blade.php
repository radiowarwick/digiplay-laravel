<div id="advancedsearchform">

  <div class="grid">
    <div class="row">
      <div class="custom-control custom-checkbox custom-control-inline">
        <input type="checkbox" id="title" value="title" name="options[]" class="custom-control-input" {{ (empty($options) or in_array("title",$options)) ? "checked" : "" }}>
        <label class="custom-control-label" for="title">Title</label>
      </div>
      <div class="custom-control custom-checkbox custom-control-inline">
        <input type="checkbox" id="artist" value="artist" name="options[]" class="custom-control-input" {{ (empty($options) or in_array("artist",$options)) ? "checked" : "" }}>
        <label class="custom-control-label" for="artist">Artist</label>
      </div>
      <div class="custom-control custom-checkbox custom-control-inline">
        <input type="checkbox" id="album" value="album"  name="options[]" class="custom-control-input" {{ (empty($options) or in_array("album",$options)) ? "checked" : "" }}>
        <label class="custom-control-label" for="album">Album</label>
      </div>
    </div>

  <div class="row">
      <div class="custom-control custom-checkbox custom-control-inline" id="types">
        <input type="checkbox" id="Music" value="Music" name="types[]" class="custom-control-input" {{ (empty($types) or in_array("Music",$types)) ? "checked" : "" }}>
        <label class="custom-control-label" for="Music">Music</label>
      </div>
      <div class="custom-control custom-checkbox custom-control-inline" id="types">
        <input type="checkbox" id="Jingle" value="Jingle" name="types[]" class="custom-control-input" {{ (!empty($types) and in_array("Jingle",$types)) ? "checked" : "" }}>
        <label class="custom-control-label" for="Jingle">Jingle</label>
      </div>
      <div class="custom-control custom-checkbox custom-control-inline" id="types">
        <input type="checkbox" id="Advert" value="Advert" name="types[]" class="custom-control-input" {{ (!empty($types) and in_array("Advert",$types)) ? "checked" : "" }}>
        <label class="custom-control-label" for="Advert">Advert</label>
      </div>
      @if(auth()->user()->hasPermission('Can schedule prerecs'))
        <div class="custom-control custom-checkbox custom-control-inline" id="types">
          <input type="checkbox" id="Prerec" value="Prerec" name="types[]" class="custom-control-input"  {{ (!empty($types) and in_array("Prerec",$types)) ? "checked" : "" }}>
          <label class="custom-control-label" for="Prerec">Prerec</label>
        </div>
      @endif
    </div>

  </div>

</div>
