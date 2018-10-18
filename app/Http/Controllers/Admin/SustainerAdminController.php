<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\SustainerSlot;

class SustainerAdminController extends Controller
{
	public function getIndex(Request $request) {
		$slots = SustainerSlot::all();

		dd($slots);
	}
}
