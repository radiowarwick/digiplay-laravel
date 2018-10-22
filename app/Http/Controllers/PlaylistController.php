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

	public function getView(Request $request, $id) {
		$playlist = Playlist::where('id', $id)->first();
		if($playlist == null) {
			abort(404, 'Page not found');
		}

		return view('playlist.view')->with([
			'playlist' => $playlist
		]);
	}
}