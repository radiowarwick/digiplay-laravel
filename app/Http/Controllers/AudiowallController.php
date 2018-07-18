<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\AudiowallSet;
use App\UserConfig;

class AudiowallController extends Controller
{
	public function getIndex(Request $request) {
		$sets = AudiowallSet::all();
		$current_audiowall_id = auth()->user()->audiowall();

		return view('audiowall.index')->with('sets', $sets)->with('current_audiowall_id', $current_audiowall_id);
	}

	public function getActivate(Request $request, $audiowall_id) {
		$set = AudiowallSet::where('id', $audiowall_id)->first();
		if(is_null($set))
			abort('404', 'Page not found');

		$user = auth()->user();
		if($set->canView($user) or $user->hasPermission('Audiowall admin')) {
			$config = UserConfig::audiowall()->where('userid', $user->id)->first();
			if(!is_null($config))
				$config->delete();

			$new_config = new UserConfig;

			$new_config->userid = $user->id;
			$new_config->val = $audiowall_id;
			$new_config->configid = 1;

			$new_config->save();

			return redirect()->route('audiowall-index')->with('messages', ['Active audiowall updated!']);
		}
		else
			abort('403', 'Not Authorised');
	}
}
