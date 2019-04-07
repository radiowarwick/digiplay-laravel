<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Log;
use Validator;

class LogController extends Controller
{
    public function getLog(Request $request) {
        $limit = (!$request->get('limit')) ? 100 : $request->get('limit');
        $query = Log::orderby('datetime', 'DESC')->limit($limit);

        $locations = (!$request->get('location')) ? [0,1,2,3,4] : explode(',', $request->get('location'));
        $query->where(function($query) use (&$locations){
            foreach($locations as $location) {
                $query->orWhere('location', $location);
            }
        });

        $array = [];
        foreach($query->get() as $l) {
            $array[] = [
                'id' => $l->id,
                'location' => $l->location,
                'location_verbose' => $l->location_verbose(),
                'title' => $l->track_title,
                'artist' => $l->track_artist,
                'datetime' => $l->datetime,
            ];
        }

        return response()->json($array);
    }

    public function postLog(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'artist' => 'required',
            'location' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'There was missing data, expecting to see "title", "artist" and "location".',
            ]);
        }

        $new_log = new Log();
        $new_log->userid = 1;
        $new_log->location = $request->input('location');
        $new_log->track_title = $request->input('title');
        $new_log->track_artist = $request->input('artist');

        if($new_log->save()) {
            return response()->json([
                'status' => 'ok',
            ]);
        }
        else {
            return response()->json([
                'status' => 'error',
                'message' => 'Could not save the log correctly.',
            ]);
        }
    }
}
