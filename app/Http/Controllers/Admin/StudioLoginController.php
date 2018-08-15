<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\StudioLogin;

class StudioLoginController extends Controller
{
	public function getIndex(Request $request) {
		$now = date('Y-m-d');
		$logins = StudioLogin::onDate($now)->where('location', 1)->get();

		return view('admin.studio.index')->with('time', $now)->with('logins', $logins);
	}

	public function postIndex(Request $request) {
		$time = $request->get('date');
		$logins = StudioLogin::onDate($time)->where('location', $request->get('location'))->get();

		return view('admin.studio.index')->with('time', $time)->with('logins', $logins);
	}
}
