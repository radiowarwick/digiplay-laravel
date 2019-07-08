<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\AudiowallSet;

class AudiowallController extends Controller
{
    public function getAudiowall(Request $request) {
        $request->validate([
            'id' => 'required',
        ]);

        $audiowall = AudiowallSet::findOrFail($request->get('id'));
    
        $json = [
            'id' => $audiowall->id,
            'name' => $audiowall->name,
            'description' => $audiowall->description,
            'walls' => [],
        ];

        foreach($audiowall->walls as $wall) {
            $wallJson = [
                'id' => $wall->id,
                'name' => $wall->name,
                'page' => $wall->page,
                'items' => [],
            ];

            foreach($wall->items as $item) {
                $itemJson = [
                    'id' => $item->id,
                    'audio_id' => $item->audio_id,
                    'item' => $item->item,
                    'text' => $item->text,
                    'foreground_colour' => $item->foregroundColour(),
                    'background_colour' => $item->backgroundColour(),
                ];

                $wallJson['items'][] = $itemJson;
            }

            $json['walls'][] = $wallJson;
        }

        return response()->json($json);
    }
}
