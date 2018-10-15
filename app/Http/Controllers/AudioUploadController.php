<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Audio;
use App\Archive;

class AudioUploadController extends Controller
{
	CONST ACCEPTABLE_MIMES = [
		'audio/flac',
		'audio/mpeg',
		'audio/mp3',
		'audio/vnd.wav'
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

			$mime = "";

			$metadata = $this->audioFileMetadata($path);
			$metadata['origin'] = auth()->user()->name;
			$metadata['random'] = mt_rand(0, 10000);
			$metadata['acceptable_bitrate'] = (256 <= $metadata['bitrate']);

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

		$metadata['acceptable_bitrate'] = (256 <= $metadata['bitrate']);
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

	public function postImport(Request $request) {
		$file = storage_path('app/uploads/' . $request->get('filename'));

		$md5 = md5_file($file);
		$md5_substring = substr($md5, 0, 1);
		$title = trim($request->get('title'));
		$artist = trim($request->get('artist'));
		$album = trim($request->get('album'));
		$type = $request->get('type');
		$censor = $request->get('censored') ? 't' : 'f';

		$errors = [];

		$existing_audio = Audio::where('md5', $md5)->get();
		if(sizeof($existing_audio) > 0)
			$errors[] = 'File already exists in digiplay.';

		if(empty($title))
			$errors[] = 'You must give the track a title.';
		if(empty($artist))
			$errors[] = 'You must give the track an artist.';
		if($type <= 0 or $type >= 5)
			$errors[] = 'Invalid type given';

		if(sizeof($errors) > 0) {
			return response()->json([
				'status' => 'error',
				'errors' => $errors
			]);
		}
		else {
			$output = storage_path('app/output/' . $md5 . '.flac');
			$conversion = 'sox "' . $file . '" -b 16 "' . $output . '" silence 1 0.1 -72d reverse silence 1 0.1 -72d reverse channels 2 rate 44100 gain -n -0.1 2>&1';
			$result = [];

			exec($conversion, $result);
			if(strpos(implode($result), 'FAIL')) {
				return response()->json([
					'status' => 'error',
					'errors' => [
						'Unable to convert the file to a flac.'
					]
				]);
			}
			$length_sample = shell_exec('soxi -s "' . $output . '"');

			$archive = Archive::where('name', 'dps0-0')->first();
			$audio = new Audio;

			$audio->title = $request->get('title');
			$audio->md5 = $md5; 
			$audio->sustainer = 'f';
			$audio->censor = $censor;
			$audio->flagged = 'f';
			$audio->creation_date = time();
			$audio->import_date = time();
			$audio->start_smpl = 0;
			$audio->end_smpl = $length_sample;
			$audio->length_smpl = $length_sample;
			$audio->intro_smpl = 0;
			$audio->extro_smpl = $length_sample;
			$audio->type = $type;
			$audio->archive = $archive->id;
			$audio->origin = auth()->user()->name;
			$audio->creator = 1;

			$audio->save();

			if(empty($album)) {
				$album = '(none)';
			}

			$audio->set_album($album);
			$audio->set_artist($artist);

			rename($output, $archive->localpath . '/' . $md5_substring . '/' . $md5 . '.flac');
			Storage::delete('uploads/' . $request->get('filename'));

			return response()->json([
				'status' => 'ok'
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