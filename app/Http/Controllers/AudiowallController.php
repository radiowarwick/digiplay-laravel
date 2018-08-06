<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\AudiowallSet;
use App\AudiowallItem;
use App\AudiowallItemColour;
use App\AudiowallWall;
use App\UserConfig;
use App\AudiowallSetPermission;
use App\User;

class AudiowallController extends Controller
{
	public function getIndex(Request $request) {
		$sets = AudiowallSet::orderby('name')->get();
		$current_audiowall_id = auth()->user()->audiowall();

		$owned = AudiowallSetPermission::where('level', 4)->where('username', auth()->user()->username)->count();

		$can_create = false;
		if($owned <= 2 or auth()->user()->hasPermission('Audiowall Admin'))
			$can_create = true;

		return view('audiowall.index')->with('sets', $sets)->with('current_audiowall_id', $current_audiowall_id)->with('can_create', $can_create);
	}

	public function getActivate(Request $request, $set_id) {
		$set = AudiowallSet::where('id', $set_id)->first();
		if(is_null($set))
			abort('404', 'Page not found');

		$user = auth()->user();
		if($set->hasView($user) or $user->hasPermission('Audiowall admin')) {
			$config = UserConfig::audiowall()->where('userid', $user->id)->first();
			if(!is_null($config))
				$config->delete();

			$new_config = new UserConfig;

			$new_config->userid = $user->id;
			$new_config->val = $set_id;
			$new_config->configid = 1;

			$new_config->save();

			return redirect()->route('audiowall-index')->with('messages', ['Active audiowall updated!']);
		}
		else
			abort('403', 'Not Authorised');
	}

	public function getSettings(Request $request, $set_id) {
		$set = AudiowallSet::where('id', $set_id)->first();
		if(is_null($set))
			abort('404', 'Page not found');
		if(!$set->hasAdmin(auth()->user()))
			abort('403', 'Not Authorised');

		return view('audiowall.settings')->with('set', $set);
	}

	public function postSettingsName(Request $request, $set_id) {
		$set = AudiowallSet::where('id', $set_id)->first();
		if(is_null($set))
			abort('404', 'Page not found');

		$this->validate($request, [
			'name' => 'required'
		]);

		$set->name = $request->name;

		$set->save();

		return redirect()->route('audiowall-settings', $set_id)->with('messages', ['Audiowall name updated!']);
	}

	public function postSettingsAdd(Request $request, $set_id) {
		$set = AudiowallSet::where('id', $set_id)->first();
		if(is_null($set))
			abort('404', 'Page not found');

		$this->validate($request, [
			'username' => [
				'required',
				'exists:users',
				Rule::unique('aw_set_permissions')->where(function($query) use (&$set_id){
					return $query->where('set_id', $set_id);
				})
			],
			'level' => 'required|integer|between:1,3'
		]);

		$permission = new AudiowallSetPermission;

		$permission->username = $request->username;
		$permission->set_id = $set_id;
		$permission->level = $request->level;

		$permission->save();

		return redirect()->route('audiowall-settings', $set_id)->with('message', ['User added']);
	}

	public function getSettingsRemove(Request $request, $set_id, $username) {
		$set = AudiowallSet::where('id', $set_id)->first();
		$removeUser = User::where('username', $username)->first();
		$thisUser = auth()->user();

		if(is_null($set) or is_null($removeUser))
			abort('404', 'Page not found');
		if($removeUser->username == $thisUser->username or !$set->hasAdmin($thisUser))
			abort('403', 'Not authorised');

		foreach($set->permissions as $permission) {
			if($permission->user->username == $removeUser->username) {
				$permission->delete();
				return redirect()->route('audiowall-settings', $set_id)->with('message', ['User removed']);
			}
		}
		return redirect()->route('audiowall-settings', $set_id)->with('message', ['User not found']);
	}

	public function postSettingsUpdate(Request $request, $set_id, $username) {
		$set = AudiowallSet::where('id', $set_id)->first();
		$removeUser = User::where('username', $username)->first();
		$thisUser = auth()->user();

		if(is_null($set) or is_null($removeUser))
			abort('404', 'Page not found');
		if($removeUser->username == $thisUser->username or !$set->hasAdmin($thisUser) or !in_array($request->level, [1,2,3]))
			abort('403', 'Not authorised');

		foreach($set->permissions as $permission) {
			if($permission->user->username == $removeUser->username) {
				$permission->level = $request->level;
				$permission->save();
				
				return redirect()->route('audiowall-settings', $set_id)->with('message', ['User permission update']);
			}
		}	
		return redirect()->route('audiowall-settings', $set_id)->with('message', ['User not found']);
	}

	public function getView(Request $request, $set_id) {
		$set = AudiowallSet::where('id', $set_id)->first();
		if(is_null($set))
			abort('404', 'Page not found');
		if(!$set->hasView(auth()->user()))
			abort('403', 'Not authorised');

		return view('audiowall.view')->with('set', $set);
	}

	public function postSaveAudiowall(Request $request, $set_id) {
		$set = AudiowallSet::where('id', $set_id)->first();
		if(is_null($set) and $request['wall'] != null)
			abort('404', 'Page not found');
		if(!$set->hasEdit(auth()->user()))
			abort('403', 'Not authorised');

		$new_audiowall = json_decode(base64_decode($request['wall']));

		// delete pre-existing data
		$this->delete_audiowall($set);

		$wall_n = 0;
		foreach($new_audiowall as $wall) {
			$new_wall = new AudiowallWall;
			
			$new_wall->set_id = $set->id;
			$new_wall->name = $wall->title;
			$new_wall->description = '';
			$new_wall->page = $wall_n++;

			$new_wall->save();

			foreach($wall->audio as $item) {
				$new_item = new AudiowallItem;

				$new_item->audio_id = $item->id;
				$new_item->wall_id = $new_wall->id;
				$new_item->text = $item->name;
				$new_item->item = $item->position;
				$new_item->style_id = 1;

				$new_item->save();

				$fg = new AudiowallItemColour;
				$fg->name = 'ForeColourRGB';
				$fg->value = hexdec($item->fg);
				$fg->item_id = $new_item->id;
				$fg->save();

				$bg = new AudiowallItemColour;
				$bg->name = 'BackColourRGB';
				$bg->value = hexdec($item->bg);
				$bg->item_id = $new_item->id;
				$bg->save();
			}
		}
	}

	function postCreateAudiowall(Request $request) {
		$owned = AudiowallSetPermission::where('level', 4)->where('username', auth()->user()->username)->count();
		if($owned > 2 and !auth()->user()->hasPermission('Audiowall Admin'))
			abort(403, 'Not authorised');

		$request->validate([
			'name' => 'required'
		]);

		$audiowall = new AudiowallSet;
		$audiowall->name = $request['name'];
		$audiowall->description = '';
		$audiowall->save();
	

		$permission = new AudiowallSetPermission;
		$permission->username = auth()->user()->username;
		$permission->level = 4;
		$permission->set_id = $audiowall->id;
		$permission->save();

		return redirect()->route('audiowall-index');
	}

	public function delete_audiowall($set) {
		foreach($set->walls as $wall) {
			foreach($wall->items as $item) {
				$item->colours()->delete();
			}
			$wall->items()->delete();
		}
		$set->walls()->delete();
	}
}
