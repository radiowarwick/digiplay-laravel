<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class EquipmentBookingController extends Controller
{
	public function getIndex(Request $request, $date='') {
		$today = Carbon::now()->startOfDay();
		
		// Generate a date to look for bookings
		// If date is invalid or in the past, set it to today
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

		return view('equipment.index')->with([
			'today' => $today,
			'date' => $date,
			'start_of_this_week' => $today->copy()->startOfWeek(),
			'start_of_week' => $date->copy()->startOfWeek(),
			'BOOKINGS_START' => env('EQUIPMENT_BOOKING_START', 8),
			'BOOKINGS_END' => env('EQUIPMENT_BOOKING_END', 22),
			'ITEMS' => explode(',', env('EQUIPMENT_BOOKING_ITEMS', 'Studio 2'))
		]);
	}
}