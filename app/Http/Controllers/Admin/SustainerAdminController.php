<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\SustainerSlot;
use App\Playlist;
use App\Audio;

class SustainerAdminController extends Controller
{
	public function getIndex(Request $request) {
		$slots = SustainerSlot::orderBy('time')->orderBy('day')->get();

		return view('admin.sustainer.index')->with([
			'slots' => $slots,
			'playlists' => Playlist::sustainer()->get()
		]);
	}

	public function postAddSlot(Request $request) {
		// Needed to override the error messages
		$this->validate($request, [
			'prerecord-id' => 'required|exists:audio,id',
			'date' => 'required',
			'time' => 'required',
		], [
			'prerecord-id.required' => 'No prerecord selected.',
			'prerecord-id.exists' => 'Precord is invalid.'
		]);

		// Attempt to convert the data
		try {
			$timestamp = Carbon::createFromFormat('Y-m-d', $request->input('date'))->startOfDay();
		}
		catch (\Exception $e) {
			return redirect()->back()->withErrors([
				'Invalid date given.'
			]);
		}

		// Add the hour chosen to the date
		try {
			$hour = (int) $request->input('time');
			$timestamp->addHours($hour);
		}
		catch(\Exception $e) {
			return redirect()->back()->withErrors([
				'Invalid time given'
			]);
		}

		// Return an error if the time is in the past
		if($timestamp->lt(Carbon::now())) {
			return redirect()->back()->withErrors([
				'The date you gave was in the past! Please make sure that it is in the future.'
			]);
		}
	}
}
