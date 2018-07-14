<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Group;
use App\Permission;
use App\GroupPermission;

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

		return redirect()->route('admin-group-index')->with('messages', ['Group has been created']);
	}

	public function getPermission(Request $request, $group_id) {
		$group = Group::where('id', $group_id)->first();
		if(is_null($group))
			abort(404);

		$permissions = Permission::all();

		return view('admin.group.permission', ['group' => $group, 'permissions' => $permissions]);
	}

	public function postPermission(Request $request, $group_id) {
		$group = Group::where('id', $group_id)->first();
		if(is_null($group))
			abort(404);

		$permission_input = $request->input('permissions');
		if(is_null($permission_input))
			$permission_input = [];
		
		foreach(Permission::all() as $permission) {
			$permission_id = $permission->id;
			$group_permission = GroupPermission::where('group_id', $group_id)->where('permission_id', $permission_id)->first();
			if(in_array($permission_id, $permission_input)) {
				if(is_null($group_permission)) {
					$new_group_permission = new GroupPermission;

					$new_group_permission->group_id = $group_id;
					$new_group_permission->permission_id = $permission_id;

					$new_group_permission->save();
				}
			}
			else if(!is_null($group_permission)) {
				$group_permission->delete();
			}

			return redirect()->route('admin-group-permission', $group_id)->with('messages', ['Permissions updated']);
		}
	}
}
