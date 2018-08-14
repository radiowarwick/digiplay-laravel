<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Audio;

class SearchController extends Controller
{
	public function postSearch(Request $request) {
		$this->validate($request, [
			'query' => 'required'
		]);

		$results = Audio::search($request)->limit(25)->get();

		$json = [];
		foreach($results as $result) {
			$entry = [];
			$entry['id'] = $result->id;
			$entry['title'] = $result->title;
			$entry['artist'] = $result->artist->name;
			$entry['album'] = $result->album->name;
			$entry['type'] = $result->type;
			$entry['length'] = $result->length();
			$entry['length_string'] = $result->length_string();

			$json[] = $entry;
		}

		return response()->json($json);
	}
}
