<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Audio;
use App\Artist;

class AudioController extends Controller
{
	CONST ACCEPTABLE_MIMES = [
		'audio/flac',
		'audio/mpeg',
		'audio/mp3',
		'audio/vnd.wav'
	];
	
	CONST ACCEPTABLE_BITRATES = [
		'audio/flac' => 1,
		'audio/mpeg' => 256,
		'audio/mp3' => 256,
		'audio/vnd.wav' => 256,
	];

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

	public function getUpload(Request $request) {
		if(!auth()->user()->hasPermission('Audio upload'))
			abort(403, 'Not authorised');

		$files = Storage::allFiles('uploads');
		$metadatas = [];
		foreach($files as $file) {
			$path = storage_path('app/' . $file);

			$metadata = $this->audioFileMetadata($path);
			$metadata['origin'] = auth()->user()->name;
			$metadata['random'] = mt_rand(0, 10000);

			$metadatas[] = $metadata;
		}

		return view('audio.upload')->with('files', $metadatas);
	}

	public function postUpload(Request $request) {
		$request->validate([
			'file' => 'required'
		]);

		$mime = $request->file('file')->getClientMimeType();
		if(!in_array($mime, AudioController::ACCEPTABLE_MIMES)) {
			return response()->json([
				'status' => 'error',
				'message' => 'Unsupported file type. Audio file must either flac, mp3 or wav'
			]);
		}

		$path = $request->file('file')->store('uploads');
		$path = storage_path('app/' . $path);
		$metadata = $this->audioFileMetadata($path);

		$metadata['acceptable_bitrate'] = (AudioController::ACCEPTABLE_BITRATES[$mime] >= $metadata['bitrate']);
		$metadata['origin'] = auth()->user()->name;
		$metadata['status'] = 'success';

		return response()->json($metadata);
	}

	private function audioFileMetadata($file_path) {
		$getID3 = new \getID3;
		$tags = $getID3->analyze($file_path);
		\getid3_lib::CopyTagsToComments($tags);

		$extracted_data = [];
		$extracted_data['filename'] = basename($file_path);
		$extracted_data['bitrate'] = isset($tags['audio']['bitrate']) ? round($tags['audio']['bitrate'] / 1000) : 0;
		$extracted_data['length'] = isset($tags['playtime_string']) ? $tags['playtime_string'] : 'Unknown';
		$extracted_data['title'] = isset($tags['comments']['title']) ? implode(';', $tags['comments']['title']) : '';
		$extracted_data['artist'] = isset($tags['comments']['artist']) ? implode(';', $tags['comments']['artist']) : '';
		$extracted_data['album'] = isset($tags['comments']['album']) ? implode(';', $tags['comments']['album']) : '';
	
		return $extracted_data;
	}
}
