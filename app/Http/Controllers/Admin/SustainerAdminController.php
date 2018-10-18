<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\SustainerSlot;
use App\Playlist;

class SustainerAdminController extends Controller
{
	public function getIndex(Request $request) {
		$slots = SustainerSlot::orderBy('time')->orderBy('day')->get();

		return view('admin.sustainer.index')->with([
			'slots' => $slots,
			'playlists' => Playlist::sustainer()->get()
		]);
	}

	public function postSaveSlot(Request $request) {
		$slot = SustainerSlot::where('id', $request->get('id'))->first();
		if(is_null($slot))
			abort(404, 'Page not found');

		$playlist = Playlist::where('id', $request->get('playlist'))->first();
		if(!is_null($playlist))
			$slot->playlistid = $playlist->id;

		$slot->save();

		return response()->json([
			'status' => 'ok',
			'colour' => $slot->playlist->colour->colour
		]);
	}
}
