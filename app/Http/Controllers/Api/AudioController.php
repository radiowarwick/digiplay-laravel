<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Audio;

class AudioController extends Controller
{
    public function getInfo(Request $request) {
        $audio = $this->getAudioFromRequest($request);
    
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
                'md5' => $audio->md5,
                'title' => $audio->title,
                'artist' => $audio->artist->name,
                'album' => $audio->album->name,
                'origin' => $audio->origin,
                'length' => $audio->length(),
                'vocal_in' => $audio->vocal_start / 44100,
                'vocal_out' => $audio->vocal_end / 44100,
                'resource' => route('api-audio-download', [
                    'key' => $request->get('key'),
                    'id' => $audio->id,
                ]),
            ]);
        }
    }

    public function getDownload(Request $request) {
        $audio = $this->getAudioFromRequest($request);
        
        if($audio === NULL) {
            return response()->json([
                'status' => 'error',
                'message' => 'Audio ID not found',
            ]);
        }
        else {
            return response()->download($audio->filePath(), $audio->id . '.flac', [
                'Content-Type: audio/flac'
            ]);
        }
    }

    private function getAudioFromRequest(Request $request) {
        if($request->get('id')) {
            return Audio::find($request->get('id'));
        }
        else if($request->get('md5')) {
            return Audio::where('md5', $request->get('md5'))->first();
        }
        return NULL;
    }
}