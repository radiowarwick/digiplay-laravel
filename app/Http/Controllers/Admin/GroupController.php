<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Group;
use App\Permission;
use App\GroupPermission;
use App\GroupUser;
use App\User;

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

	public function getMembers(Request $request, $group_id) {
		$group = Group::where('id', $group_id)->first();
		if(is_null($group))
			abort(404);
		$members = $group->users;
		
		return view('admin.group.members', ['group' => $group, 'members' => $members]);
	}

	public function postAddMember(Request $request, $group_id) {
		$group = Group::where('id', $group_id)->first();
		if(is_null($group))
			abort(404);

		$this->validate($request, [
			'username' => 'required|exists:users'
		]);

		$user = User::where('username', $request->input('username'))->first();
		$group_user = GroupUser::where('group_id', $group_id)->where('username', $user->username)->first();

		if(!is_null($group_user))
			$message = 'User is already in the group';
		else {
			$message = 'User added to group';

			$group_user = new GroupUser;

			$group_user->group_id = $group_id;
			$group_user->username = $user->username;

			$group_user->save();
		}

		return redirect()->route('admin-group-members', $group_id)->with('messages', [$message]);
	}

	public function getRemoveMember(Request $request, $group_id, $username) {
		$group_member = GroupUser::where('group_id', $group_id)->where('username', $username)->first();

		if(is_null($group_member))
			$message = 'User is not in group';
		else {
			$group_member->delete();
			$message = 'User removed from group';
		}

		return redirect()->route('admin-group-members', $group_id)->with('messages', [$message]);
	}
}
