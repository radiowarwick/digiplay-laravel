<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Playlist;
use App\PlaylistAudio;

class PlaylistController extends Controller {
	public function __construct() {
		$this->middleware('permission:Playlist editor');
	}

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

		$playlistAudio = $playlist->playlistAudio()->paginate(25)->appends($_GET);

		return view('playlist.view')->with([
			'playlist' => $playlist,
			'playlistAudio' => $playlistAudio
		]);
	}

	public function postRemove(Request $request) {
		$playlistAudio = PlaylistAudio::where('id', $request->get('id'));
		if($playlistAudio == null) {
			return response()->json([
				'status' => 'error'
			]);
		}

		$playlistAudio->delete();

		return response()->json([
			'status' => 'ok'
		]);
	}

	public function postUpdate(Request $request) {
		if($request->get('remove') == "true") {
			$playlistAudio = PlaylistAudio::where('audioid', $request->get('audio_id'))->where('playlistid', $request->get('playlist_id'))->first();
			$playlistAudio->delete();

			return response()->json([
				'removed' => 'true'
			]);
		}
		else {
			$playlistAudio = new PlaylistAudio;
			$playlistAudio->playlistid = $request->get('playlist_id');
			$playlistAudio->audioid = $request->get('audio_id');
			$playlistAudio->save();

			return response()->json([
				'removed' => 'false'
			]);
		}
	}
}