<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AudioUploadController extends Controller
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

	public function __construct() {
		$this->middleware('permission:Audio admin');
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
		if(!in_array($mime, AudioUploadController::ACCEPTABLE_MIMES)) {
			return response()->json([
				'status' => 'error',
				'message' => 'Unsupported file type. Audio file must either flac, mp3 or wav'
			]);
		}

		$original_filename = $request->file('file')->getClientOriginalName();
		$original_filename = $this->normalizeString($original_filename);
		$dot = strpos($original_filename, '.');
		$original_filename = substr_replace($original_filename, ' ' . mt_rand(1, 1000), $dot, 0);

		$path = $request->file('file')->storeAs('uploads', $original_filename);
		$path = storage_path('app/' . $path);
		$metadata = $this->audioFileMetadata($path);

		$metadata['acceptable_bitrate'] = (AudioUploadController::ACCEPTABLE_BITRATES[$mime] >= $metadata['bitrate']);
		$metadata['origin'] = auth()->user()->name;
		$metadata['status'] = 'success';
		$metadata['random'] = mt_rand(0, 10000);

		return response()->json($metadata);
	}

	public function postDelete(Request $request) {
		$filename = $request->get('filename');
		if(Storage::delete('uploads/' . $filename)) {
			return response()->json([
				'status' => 'ok'
			]);
		}
		else {
			return response()->json([
				'status' => 'fail'
			]);
		}
	}

	private function normalizeString($string) {
		$string = str_replace(['\\', '?', '!', ':', '"', '\'', '*', '[', ']', '/', ':', ';', ','], '', $string);
		return $string;
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