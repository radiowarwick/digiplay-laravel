<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ViewAudio;

class SearchController extends Controller
{
	public function postSearch(Request $request) {
		$this->validate($request, [
			'query' => 'required'
		]);

		$allowed_types = ['Music', 'Jingle', 'Advert', 'Prerec'];
		$filtered_types = [];
		foreach($request['types'] as $type) {
			if(in_array($type, $allowed_types))
				$filtered_types[] = $type;
		}

		$search_string = '%' . trim($request['query']) . '%';
		$allowed_criteria = ['title', 'album', 'artist'];
		$filtered_criteria = [];
		foreach($request['criteria'] as $criteria) {
			if(in_array($criteria, $allowed_criteria))
				$filtered_criteria[] = $criteria;
		}

		$results = ViewAudio::where(function($query) use (&$filtered_types){
			foreach ($filtered_types as $type) {
				$query->orWhere('audiotype', $type);
			}
		})->where(function($query) use (&$filtered_criteria, &$search_string){
			foreach($filtered_criteria as $criteria) {
				$query->orWhere($criteria, 'ILIKE', $search_string);
			}
		})->orderby('id', 'DESC')
		->limit(25)
		->get();

		$json = [];
		foreach($results as $result) {
			$entry = [];
			$entry['id'] = $result->id;
			$entry['title'] = $result->title;
			$entry['artist'] = $result->artist;
			$entry['album'] =  $result->album;
			$entry['type'] = $result->audiotype;
			$entry['length'] = $result->audio->length();
			$entry['length_string'] = $result->audio->length_string();

			$json[] = $entry;
		}

		return response()->json($json);
	}
}
