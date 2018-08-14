<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Config;

class StudioController extends Controller
{
	function __construct() {
		$this->middleware(function($request, $next){
			$key = $request->route('key');
			if(is_null($key))
				return $next($request);

			$location = Config::getLocationByKey($key);
			if(is_null($location))
				abort(404, 'Page not found');

			$request->attributes->add(['location' => $location]);
			return $next($request);
		});
	}

	function getLogin(Request $request, $key) {
		$location = $request->get('location');

		return view('studio.login')->with('location', $location)->with('key', $key);
	}

	function postLogin(Request $request, $key) {
		$this->validate($request, [
			'username' => 'required',
			'password' => 'required',
		]);

		
		if (auth()->attempt($request->only(['username', 'password']), true)) {
			$location = $request->get('location');

			Config::updateLocationValue($location, 'userid', auth()->user()->id);
			Config::updateLocationValue($location, 'user_aw_set', auth()->user()->audiowall());

			$can_update = 'false';
			if(auth()->user()->hasPermission('Music Admin'))
				$can_update = 'true';
			Config::updateLocationValue($location, 'can_update', $can_update);

			return redirect()->route('studio-view', $key);
		}

		return redirect()->back()->withErrors('Username and/or Password is wrong!');
	}

	public function getLogout(Request $request, $key) {
		auth()->logout();

		$location = $request->get('location');
		Config::updateLocationValue($location, 'userid', 0);
		Config::updateLocationValue($location, 'user_aw_set', 0);
		Config::updateLocationValue($location, 'can_update', 'false');

		return redirect()->route('studio-login', $key);
	}

	public function getView(Request $request, $key) {
		dd("TODO");
	}
}
