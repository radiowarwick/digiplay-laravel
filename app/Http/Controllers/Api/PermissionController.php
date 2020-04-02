<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class PermissionController extends Controller
{
	public function getPermission(Request $request) {
		$this->validate($request, [
			'username' => 'required'
		]);

		$user = User::where('username', $request->input('username'))->first();
		if(is_null($user)) {
			return "User not found"
		}

		if($user->hasPermission('Can connect to outside broadcasts')) {
			return "True"
		}
		

		return "False";
	}
}
