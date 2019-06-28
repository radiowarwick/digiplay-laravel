<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Audio;

class LogController extends Controller
{
    public function getInfo(Request $request) {
        $audio = Audio::find($request->get('id'));
        
        if($audio === NULL) {
            return response()->json([
                'status' => 'error',
                'message' => 'Audio ID not found',
            ]);
        }
        else {
            return response()->json([
                'status' => 'ok',
                'id' => $audio->id,
                'title' => $audio->title,
                'artist' => $audio->artist->name,
                'album' => $audio->album->name,
                'origin' => $audio->origin,
                'length' => $audio->length(),
                'vocal_in' => $audio->vocal_start / 44100,
                'vocal_out' => $audio->vocal_end / 44100,

            ]);
        }
    }

    public function getDownload(Request $request) {
        $audio = Audio::findOrFail($request->get('id'));
		return response()->download($audio->filePath(), $id . '.flac', [
			'Content-Type: audio/flac'
		]);
    }
}