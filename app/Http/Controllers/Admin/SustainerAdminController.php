<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Prerecord;

class SustainerAdminController extends Controller
{
	public function getIndex(Request $request) {
		$prerecords = Prerecord::where('scheduled_time', '>', time())->orderBy('scheduled_time')->get();

		return view('admin.sustainer.index')->with([
			'prerecords' => $prerecords,
		]);
	}

	public function getArchive(Request $request) {
		// Get prerecords, that where in the past, order newest to oldest, paginate results
		$prerecords = Prerecord::where('scheduled_time', '<', time())->orderBy('scheduled_time', 'DESC')->paginate(25);

		return view('admin.sustainer.archive')->with([
			'prerecords' => $prerecords,
		]);
	}

	public function postSchedule(Request $request) {
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

		$check = Prerecord::where('scheduled_time', $timestamp->timestamp)->first();
		if($check !== NULL) {
			return redirect()->back()->withErrors([
				'The date and time you gave already has a prerecord scheduled.'
			]);
		}

		$new_prerecord = new Prerecord;
		$new_prerecord->scheduled_time = $timestamp->timestamp;
		$new_prerecord->audio_id = $request->input('prerecord-id');
		$new_prerecord->scheduler = auth()->user()->username;
		$new_prerecord->save();

		return redirect()->back();
	}

	public function postUnschedule(Request $request) {
		$this->validate($request, [
			'prerecord-id' => 'required|exists:prerecords,id'
		], [
			'prerecord-id.required' => 'Something went wrong when unscheduling the prerecord.',
			'prerecord-id.exists' => 'You tried to unschedule a prerecord which does not exist.'
		]);

		$prerecord = Prerecord::where('id', $request->input('prerecord-id'))->first();
		$prerecord->delete();

		return redirect()->back();
	}
}
