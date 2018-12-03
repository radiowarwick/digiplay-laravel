<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Adldap\Laravel\Facades\Adldap;

class UserController extends Controller
{
	const ATTRIBUTES = [
		'universitynumber',
		'displayname',
		'cn',
		'givenname',
		'mail',
		'homedirectory'
	];

	public function getUser(Request $request) {
		$username = $request->get('username');
		if(is_null($username))
			abort(404, 'Page not found');

		$user = Adldap::search()->where('uid', '=', $username)->get()->first();
		if(!is_null($user))
			return view('admin.user.view')->with('user', $user)->with('attributes', $this::ATTRIBUTES);
		else
			return view('admin.user.error');
	}

	public function postUpdate(Request $request) {
		$user = Adldap::search()->where('uid', '=', $request->get('username'))->get()->first();
		if(is_null($user))
			abort(404, 'Page not found');

		$user->setAttribute($request->get('key'), $request->get('value'), 0);
		$user->save();
		return redirect()->back();
	}
}
