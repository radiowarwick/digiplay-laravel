<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Audio;
use App\PlaylistAudio;

class SearchController extends Controller
{
	public function postSearch(Request $request) {
		$this->validate($request, [
			'query' => 'required'
		]);

		if(is_null($request->get('limit')))
			$limit = 25;
		else
			$limit = $request->get('limit');

		$results = Audio::search($request)->limit($limit)->get();

		$json = [];
		foreach($results as $result) {
			$entry = [];
			$entry['id'] = $result->id;
			$entry['title'] = $result->title;
			$entry['artist'] = $result->artist->name;
			$entry['album'] = $result->album->name;
			$entry['type'] = $result->type;
			$entry['length'] = $result->length();
			$entry['length_string'] = $result->lengthString();
			$entry['censor'] = $result->censor;

			$json[] = $entry;
		}

		return response()->json($json);
	}

	public function postDetail(Request $request) {
		$this->validate($request, [
			'id' => 'required'
		]);

		$result = Audio::where('id', $request->get('id'))->first();
		if(is_null($result))
			return response()->json([
				'status' => 'error'
			]);

		$entry = [];
		$entry['status'] = 'ok';
		$entry['id'] = $result->id;
		$entry['title'] = $result->title;
		$entry['artist'] = $result->artist->name;
		$entry['album'] = $result->album->name;
		$entry['type'] = $result->type;
		$entry['length'] = $result->length();
		$entry['length_string'] = $result->lengthString();
		$entry['censor'] = $result->censor;


		return response()->json($entry);
	}

	public function postPlaylist(Request $request) {
		$this->validate($request, [
			'id' => 'required'
		]);

		$playlists = PlaylistAudio::where('audioid', $request->get('id'))->get();
		$response = [];
		foreach($playlists as $playlist) {
			$response[] = $playlist->playlistid;
		}

		return response()->json($response);
	}
}
