<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Group;

class GroupController extends Controller
{
	public function getIndex() {
		$groups = Group::all();

		return view('admin.group.index', ['groups' => $groups]);
	}

	public function postCreate(Request $request) {
		$this->validate($request, [
			'name' => 'required|unique:new_groups'
		]);

		$group = new Group;
		$group->name = $request->input('name');
		$group->save();

		return redirect()->route('admin-group-index');
	}
}
