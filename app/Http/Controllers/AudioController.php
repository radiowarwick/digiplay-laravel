<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Audio;
use App\Artist;
use App\ViewAudio;

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
		$selectedOptions = $request->input('options');
		$selectedTypes = $request->input('types');

		if(is_null($searchTerm) or empty($selectedOptions) or empty($selectedTypes)) {
			if(is_null($searchTerm))
				$searchTerm = '';
			return view('audio.invalid-search', ['q' => $searchTerm]);
		}
	
		$params = array(
			'query' => $searchTerm,
			'type' => $selectedTypes,
			'filter' => $selectedOptions
	  	);

		$audioResults = Audio::search($params);

		$total = $audioResults->count();
		$paginateResults = $audioResults->paginate(25)->appends($_GET);

		return view('audio.search', ['results' => $paginateResults, 'total' => $total, 'q' => $searchTerm, 'options' => $selectedOptions, 'types' => $selectedTypes]);
	}

	public function getPreview(Request $request, $id) {
		$audio = Audio::where('id', $id)->first();
		if($audio === null)
			abort(404, 'Page not found');

		$file = $audio->filePath();
		$start = $audio->start_smpl / 44100;
		$end = $audio->end_smpl / 44100;

		$multi = 6;
		$bitrate = '48';
		if(auth()->user()->hasPermission('High quality audio')) {
			$multi = 24;
			$bitrate = '192';
		}

		$command = 'sox ' . $file . ' -t mp3 -C ' . $bitrate . '.5 - trim ' . $start . ' ' . $end;
		return response()->stream(function() use ($command){
			$pfile = popen($command, 'r');
			fpassthru($pfile);
			if(is_resource($pfile))
				pclose($pfile);
		}, 200, [
			'Content-type' => 'audio/mpeg',
			'Content-length' => (int) ($multi * 1000 * $audio->length()),
			'Accept-Ranges' => 'bytes'
		]);
	}
}

