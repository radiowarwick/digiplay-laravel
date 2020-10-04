<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Playlist;
use App\Prerecord;
use App\Audio;

class PlaylistController extends Controller
{
    public function getPlaylist(Request $request) {
        if(is_null($id = $request->get('id'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'No playlist id specified in the "id" parameter.',
            ]);
        }

        $playlist = Playlist::where('id', $id)->first();
        if(!$playlist) {
            return response()->json([
                'status' => 'error',
                'message' => 'Playlist could not be found, please give a valid id.',
            ]);
        }

        // If format is pls return that format
        // otherwise return a JSON result
        if($request->get('format') == 'pls') {
            $text = "[playlist]\n\n";
            
            $i = 1;
            foreach($playlist->audio as $a) {
                $text .= 'File' . $i . '=' . $a->filePath() . "\n\n";
                $i += 1;
            }
            
            $text .= 'NumberOfEntries=' . $i . "\n";
            $text .= "Version=2\n";
            
            return response($text)->header('Mime-Type', 'audio/x-scpls');
        }
        else {
            $tracks = [];
            foreach($playlist->audio as $a) {
                $tracks[] = [
                    'id' => $a->id,
                    'title' => $a->title,
                    'artist' => $a->artist->name,
                    'file_path' => $a->filePath(),
                ];
            }
    
            return response()->json($array);
        }
    }

    public function getJingles(Request $request) {
        $query = DB::select("SELECT * FROM v_audio_jingles WHERE enabled='t'");

        if(count($query) < 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Database returned no jingles.',
            ]);
        }

        $array = [];
        foreach($query as $a) {
            $array[] = [
                'id' => $a->id,
                'title' => $a->title,
                'artist' => 'RAW',
                'file_path' => $a->path . '/' . substr($a->md5, 0, 1) . '/' . $a->md5 . '.flac',
            ];
        }

        if($request->get('format') === 'pls') {
                $text = "[playlist]\n\n";

                $i = 1;
                foreach($array as $track) {
                        $text .= 'File' . $i . '=' . $track["file_path"] . "\n\n";
                        $i += 1;
                }

                $text .= 'NumberOfEntries=' . $i . "\n";
                $text .= "Version=2\n";

                return response($text)->header('Mime-Type', 'audio/x-scpls');
        }
        else {
            return response()->json($array);
        }
    }

    public function getSustainer(Request $request) {
        $offset = (!$request->get('offset')) ? 0 : $request->get('offset');
        $now = \Carbon\Carbon::now()->startOfHour();
        $now->addHours($offset);

        $prerecord = Prerecord::where('scheduled_time', $now->timestamp)->first();

        if($prerecord) {
            $path = response($prerecord->audio->filePath());
        }
        else {
            // If we have no prerecord
            // Then we play the top of the hour jingle
            // Set in the environment
            // 54002 - ID of the 10 second top of the hour jingle
            $path = Audio::find(env('TOP_OF_THE_HOUR_ID', 54002))->filePath();
        }

        $text = "[playlist]\n\nFile1=" . $path . "\n\nNumberOfEntries=1\nVersion=2\n";

        return response($text)->header('Mime-Type', 'audio/x-scpls');
    }

    private function sanatize($string) {
        return preg_replace("/[^A-Za-z0-9 ]/", '', $string);
    }
}
