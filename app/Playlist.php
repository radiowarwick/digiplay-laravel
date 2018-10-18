<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
	protected $table = 'playlists';
	protected $primaryKey = 'id';
	public $timestamps = false;

	public function audio() {
		return $this->hasManyThrough('App\Audio', 'App\PlaylistAudio', 'playlistid', 'id', 'id', 'audioid');
	}

	public function colour() {
		return $this->hasOne('App\PlaylistColour', 'playlistid', 'id');
	}

	public function scopeStudio($query) {
		return $query->where('sustainer', 'f')->orderBy('sortorder');
	}

	public function scopeSustainer($query) {
		return $query->where('sustainer', 't')->orderBy('sortorder');
	}
}
