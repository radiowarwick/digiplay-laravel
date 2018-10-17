<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Album;

class Audio extends Model
{
	protected $table = 'audio';
	protected $primaryKey = 'id';
	public $timestamps = false;
	
	public function audioArtist() {
		return $this->hasOne('App\AudioArtist', 'audioid', 'id');
	}

	public function artist() {
		return $this->audioArtist->artist();
	}

	public function album() {
		return $this->hasOne('App\Album', 'id', 'music_album');
	}

	// annoying name to not share column name
	public function theArchive() {
		return $this->hasOne('App\Archive', 'id', 'archive');
	}

	public function filePath() {
		if($this->archive !== 0) {
			$folder = substr($this->md5, 0, 1);
			return $this->theArchive->localpath . '/' . $folder . '/' . $this->md5 . '.flac';
		}
		return null;
	}

	public function scopeTracks($query) {
		return $query->where('type', 1);
	}

	public function length() {
		return ($this->end_smpl - $this->start_smpl) / 44100;
	}

	public function lengthString() {
		$length = $this->length();
		$string = '';

		$seconds = $length % 60;
		$length = floor($length / 60);
		$string = sprintf('%02d', $seconds) . 's';

		// length bigger than 0 so has minutes
		if($length > 0) {
			$minutes = $length % 60;
			$length = floor($length / 60);
			$string = $minutes . 'm ' . $string;
		}
		// length bigger than 0 so has hours
		if($length > 0)
			$string = sprintf('%02d', $length) . 'h ' . $string;

		return $string;
	}

	/*
	*   Params is an associative array. Indexes are:
	*   "query"     =>  (string) name to search
	*   "type"      =>  (array) types to search, as string, Song, Prerec, Jingle, Advert
	*   "filter"    =>  (array) types of filter, as string, title, artist, album
	*   "censor"    =>  (boolean) true to include censored tracks
	*/
	public function scopeSearch($query, $params) {
		$allowed_types = [null, 'Music', 'Jingle', 'Advert', 'Prerec'];
		$filtered_types = [];

		// Fill filtered types with index of each allowed type
		foreach($params['type'] as $type) {
			if(in_array($type, $allowed_types))
				$filtered_types[] = array_search($type, $allowed_types);
		}

		$query->where(function($query) use (&$filtered_types){
			foreach($filtered_types as $type) {
				$query->orWhere('type', $type);
			}
		});

		$strip_query = '%' . trim($params['query']) . '%';
		$filters = $params['filter'];

		// setup filter joins
		foreach($filters as $filter) {
			if($filter == 'artist') {
				$query->join('audioartists', 'audio.id', '=', 'audioartists.audioid')
					->join('artists', 'audioartists.artistid', '=', 'artists.id');
			}
			else if($filter == 'album') {
				$query->join('albums', 'audio.music_album', '=', 'albums.id');
			}
		}

		// Apply filter if param is not set or (if set) value is not "false"
		if(!(isset($params['censor']) and $params['censor'] == "false"))
			$query->where('censor', 'f');

		// do filter wheres
		$query->where(function($query) use (&$filters, &$strip_query){
			foreach($filters as $filter) {
				if($filter == 'title') {
					$query->orWhere('title', 'ILIKE', $strip_query);
				}
				else if($filter == 'artist') {
					$query->orWhere('artists.name', 'ILIKE', $strip_query);
				}
				else if($filter == 'album') {
					$query->orWhere('albums.name', 'ILIKE', $strip_query);
				}
			}
		});

		return $query->orderBy('audio.id', 'DESC')->select('audio.*');
	}

	public function set_album($album_str) {
		$album = Album::where('name', $album_str)->first();
		if(!isset($album)) {
			$album = new Album;
			$album->name = $album_str;
			$album->save();
		}

		$this->music_album = $album->id;
		$this->save();
	}

	public function set_artist($artist_str) {
		$artist = Artist::where('name', $artist_str)->first();
		if(!isset($artist)) {
			$artist = new Artist;
			$artist->name = $artist_str;
			$artist->save();
		}

		$audio_artist = AudioArtist::where('audioid', $this->id)->first();
		if(!isset($audio_artist)) {
			$audio_artist = new AudioArtist;
			$audio_artist->audioid = $this->id;
		}

		$audio_artist->artistid = $artist->id;
		$audio_artist->save();
	}

	public function getTypeString() {
		$typeID = $this->type;
		$types = array('Music', 'Jingle', 'Advert', 'Prerec');
		return $types[$typeID-1];
	}
}