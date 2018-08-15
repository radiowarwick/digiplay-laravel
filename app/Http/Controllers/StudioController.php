<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Config;
use App\StudioLogin;

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

			$canUpdate = 'false';
			if(auth()->user()->hasPermission('Music Admin'))
				$canUpdate = 'true';
			Config::updateLocationValue($location, 'can_update', $canUpdate);

			$studioLogin = new StudioLogin;
			$studioLogin->username = auth()->user()->username;
			$studioLogin->location = $location;
			$studioLogin->save();

			return redirect()->route('studio-view', $key);
		}

		return redirect()->back()->withErrors('Username and/or Password is wrong!');
	}

	public function getLogout(Request $request, $key) {
		$location = $request->get('location');
		
		$studioLogin = StudioLogin::where('logout_at', NULL)->where('username', auth()->user()->username)->where('location', $location)->first();
		$studioLogin->logout_at = now();
		$studioLogin->save();
		
		Config::updateLocationValue($location, 'userid', 0);
		Config::updateLocationValue($location, 'user_aw_set', 0);
		Config::updateLocationValue($location, 'can_update', 'false');

		auth()->logout();
		return redirect()->route('studio-login', $key);
	}

	public function getView(Request $request, $key) {
		$location = $request->get('location');

		return view('studio.view')->with('key', $key)->with('location', $location);
	}
}
