<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Config;
use App\StudioLogin;
use App\Email;

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

			$studio_login = new StudioLogin;
			$studio_login->username = auth()->user()->username;
			$studio_login->location = $location;
			$studio_login->save();

			return redirect()->route('studio-view', $key);
		}

		return redirect()->back()->withErrors('Username and/or Password is wrong!');
	}

	public function getLogout(Request $request, $key) {
		$location = $request->get('location');
		
		$studio_login = StudioLogin::where('logout_at', NULL)->where('username', auth()->user()->username)->where('location', $location)->first();
		$studio_login->logout_at = now();
		$studio_login->save();
		
		Config::updateLocationValue($location, 'userid', 0);
		Config::updateLocationValue($location, 'user_aw_set', 0);
		Config::updateLocationValue($location, 'can_update', 'false');

		auth()->logout();
		return redirect()->route('studio-login', $key);
	}

	public function getView(Request $request, $key) {
		$location = $request->get('location');

		$emails = Email::latest()->get();
		$censor_start = Config::where('location', '-1')->where('parameter', 'censor_start')->first()->val;
		$censor_end = Config::where('location', '-1')->where('parameter', 'censor_end')->first()->val;

		return view('studio.view')->with([
			'key' => $key,
			'location' => $location,
			'emails' => $emails,
			'censor_start' => $censor_start,
			'censor_end' => $censor_end
		]);
	}

	public function getMessage(Request $request, $key, $id) {
		$email = Email::find($id);
		if(is_null($email))
			abort(404, 'Page not found');

		if($email->new_flag == 't') {
			$email->new_flag = 'f';
			$email->save();
		}

		return response()->json([
			'id' => $email->id,
			'subject' => $email->subject,
			'body' => strip_tags($email->body)
		]);
	}
}
