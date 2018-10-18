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
}
