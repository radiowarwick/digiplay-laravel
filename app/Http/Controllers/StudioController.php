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

			$location = Config::get_location_by_key($key);
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
			$location_configs = Config::location($request->get('location'));

			$userid_config = $location_configs->where('parameter', 'userid')->first();
			$userid_config->val = auth()->user()->id;
			$userid_config->save();

			$aw_config = $location_configs->where('parameter', 'user_aw_set')->first();
			$aw_config->val = 0; //TODO
			$aw_config->save();

			return redirect()->route('studio-view');
		}

		return redirect()->back()->withErrors('Username and/or Password is wrong!');
	}

	public function getView(Request $request, $key) {
		dd("TODO");
	}
}
