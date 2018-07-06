<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Audio;
use App\Artist;

class AudioController extends Controller
{
    public function getIndex(Request $request) {
    	$latestTracks = Audio::tracks()
    		->orderby('creation_date', 'DESC')
    		->limit(10)
    		->get();

    	return view('audio.index', ['latest' => $latestTracks]);
    }

    public function getSearch(Request $request) {
    	$searchTerm = $request->input('q');

    	if(is_null($searchTerm) or strlen($searchTerm) <= 3) {
    		if(is_null($searchTerm))
    			$searchTerm = '';
    		return view('audio.invalid-search', ['q' => $searchTerm]);
    	}

    	$titleResults = Audio::where([
    		['title', 'ILIKE', '%'.trim($searchTerm).'%'],
    		['type', 1]
    	])
    		->orderby('creation_date', 'DESC');

    	$total = $titleResults->count();
    	$paginateResults = $titleResults->paginate(10);
    	
    	return view('audio.search', ['results' => $paginateResults, 'total' => $total, 'q' => $searchTerm]);
    }
}
