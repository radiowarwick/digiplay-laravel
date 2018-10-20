<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Playlist;

class PlaylistController extends Controller {
	public function getIndex(Request $request) {

		$studioPlaylists = Playlist::studio()->get();
		$sustainerPlaylists = Playlist::sustainer()->get();

		return view('playlist.index')->with([
			'studio' => $studioPlaylists,
			'sustainer' => $sustainerPlaylists
		]);
	}
}