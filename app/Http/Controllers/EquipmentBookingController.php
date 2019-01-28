<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class EquipmentBookingController extends Controller
{
	private $items, $start, $end;

	public function __construct() {
		$this->items = explode(',', env('EQUIPMENT_BOOKING_ITEMS', 'Studio 2'));
		$this->start = env('EQUIPMENT_BOOKING_START', 8);
		$this->end = env('EQUIPMENT_BOOKING_END', 22);
	}

	public function getIndex(Request $request, $date='') {
		$today = Carbon::now()->startOfDay();
		$date = $this->date($date);

		return view('equipment.index')->with([
			'today' => $today,
			'date' => $date,
			'start_of_this_week' => $today->copy()->startOfWeek(),
			'start_of_week' => $date->copy()->startOfWeek(),
			'BOOKINGS_START' => $this->start,
			'BOOKINGS_END' => $this->end,
			'ITEMS' => $this->items
		]);
	}

	public function postBook(Request $request, $date='') {
		$today = Carbon::now()->startOfDay();
		$date = $this->date($date);

		for($i = 0; $i < sizeof($this->items); $i++) {
			for($j = $this->start; $j <= $this->end; $j++) {
				dump($date->copy()->addHours($j)->timestamp);
			}
			dump($request->input('book' . $i));
		}
	}

	// Generate a date to look for bookings
	// If date is invalid or in the past, set it to today
	private function date($date) {
		$today = Carbon::now()->startOfDay();

		if($date !== '') {
			try {
				$date = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
				// Check that date is not in the past
				if($date->lt($today)) {
					$date = $today;
				}
			}
			catch(\Exception $e) {
				$date = $today;
			}
		}
		else {
			$date = $today;
		}
		return $date;
	}
}