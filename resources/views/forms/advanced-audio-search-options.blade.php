<div id="advancedsearchform">

  <div class="grid">
    <div class="row">
      <div class="custom-control-inline">
        <input type="checkbox" id="title" value="title" name="options[]"  {{ (empty($options) or in_array("title",$options)) ? "checked" : "" }}>
         Title
      </div>
      <div class="custom-control-inline">
        <input type="checkbox" id="artist" value="artist" name="options[]"  {{ (empty($options) or in_array("artist",$options)) ? "checked" : "" }}>
        Artist
      </div>
      <div class="custom-control-inline">
        <input type="checkbox" id="album" value="album"  name="options[]"  {{ (empty($options) or in_array("album",$options)) ? "checked" : "" }}>
        Album
      </div>
    </div>

  <div class="row">
      <div class="custom-control-inline" id="types">
        <input type="checkbox" id="Music" value="Music" name="types[]"  {{ (empty($types) or in_array("Music",$types)) ? "checked" : "" }}>
        Music
      </div>
      <div class="custom-control-inline" id="types">
        <input type="checkbox" id="Jingle" value="Jingle" name="types[]"  {{ (!empty($types) and in_array("Jingle",$types)) ? "checked" : "" }}>
        Jingle
      </div>
      <div class="custom-control-inline" id="types">
        <input type="checkbox" id="Advert" value="Advert" name="types[]"  {{ (!empty($types) and in_array("Advert",$types)) ? "checked" : "" }}>
        Advert
      </div>
      @if(auth()->user()->hasPermission('Can schedule prerecs'))
        <div class="custom-control-inline" id="types">
          <input type="checkbox" id="Prerec" value="Prerec" name="types[]"   {{ (!empty($types) and in_array("Prerec",$types)) ? "checked" : "" }}>
          Prerec
        </div>
      @endif
    </div>

  </div>

</div>
