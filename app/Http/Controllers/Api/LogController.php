<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Log;

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
                'location' => $l->location,
                'location_verbose' => $l->location_verbose(),
                'title' => $l->track_title,
                'artist' => $l->track_artist,
                'datetime' => $l->datetime,
            ];
        }

        return response()->json($array);
    }
}
