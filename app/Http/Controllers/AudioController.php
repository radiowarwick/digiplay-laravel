<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Audio;

class AudioController extends Controller
{
    public function getIndex(Request $request) {
    	return view('audio.index');
    }

    public function getSearch(Request $request) {
    	$searchTerm = $request->input('q');

    	$results = Audio::where([
    		['title', 'ILIKE', '%'.trim($searchTerm).'%'],
    		['type', '=', 1]
    	]);

    	$total = $results->count();
    	$paginateResults = $results->paginate(10)->withPath($request->url() . '?q=' . $searchTerm);
    	
    	return view('audio.search', ['results' => $paginateResults, 'total' => $total, 'q' => $searchTerm]);
    }
}
