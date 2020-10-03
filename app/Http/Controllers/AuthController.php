<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

use App\User;

class AuthController extends Controller
{
	public function getLogin(Request $request) {
		return view('login');
	}

	public function postLogin(Request $request) {
		// Validate credentials
		$this->validate($request, [
			'username' => 'required',
			'password' => 'required',
		]);

		// Allow login as anyoe if in the local environment
		if(app()->environment('local')) {
			$user = User::where('username', $request->get('username'))->first();
			if(is_null($user)) {
				return abort();
			}

			if(auth()->loginUsingId($user->id)) {
				return redirect()->route('index');
			}
		}

		
		if (auth()->attempt($request->only(['username', 'password']), true)) {
			return redirect()->route('index');
		}

		return redirect()->back()->withErrors(
			 'Username and/or Password is wrong!'
		);
	}

	public function getLogout(Request $request) {
		auth()->logout();
		return redirect()->route('login')->with('status', 'Logged out successfully!');
	}
}
