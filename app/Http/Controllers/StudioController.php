<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Config;
use App\StudioLogin;
use App\Email;
use App\Showplan;
use App\ShowplanItem;
use App\Log;
use App\Playlist;

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

		$showplan_id = Config::where('parameter', 'default_showplan')->where('location', $location)->first()->val;
		$showplan = Showplan::find($showplan_id);
		$showplan->items()->delete();

		auth()->logout();
		return redirect()->route('studio-login', $key);
	}

	public function getView(Request $request, $key) {
		$location = $request->get('location');

		$emails = Email::latest()->get();
		$censor_start = Config::where('location', '-1')->where('parameter', 'censor_start')->first()->val;
		$censor_end = Config::where('location', '-1')->where('parameter', 'censor_end')->first()->val;
		$showplan_id = Config::where('parameter', 'default_showplan')->where('location', $location)->first()->val;
		$showplan = Showplan::find($showplan_id);
		$log = Log::where('location', $location)->orderBy('id', 'DESC')->limit(50)->get();
		$playlists = Playlist::studio()->get();
		$showplans = auth()->user()->showplans(true);

		return view('studio.view')->with([
			'key' => $key,
			'location' => $location,
			'emails' => $emails,
			'censor_start' => $censor_start,
			'censor_end' => $censor_end,
			'showplan' => $showplan,
			'log' => $log,
			'playlists' => $playlists,
			'showplans' => $showplans
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
			'subject' => strip_tags($email->subject),
			'body' => strip_tags($email->body)
		]);
	}

	public function getLatestMessages(Request $request, $key, $id) {
		$emails = Email::where('id', '>', $id)->get();
		$json = [];

		foreach ($emails as $email) {
			$json[] = [
				'id' => $email->id,
				'subject' => strip_tags($email->subject),
				'sender' => strip_tags($email->sender),
				'date' => date('d/m/y H:i', $email->datetime)
			];
		}

		return response()->json($json);
	}

	public function getAddShowplan(Request $request, $key, $id) {
		$showplan_id = Config::where('parameter', 'default_showplan')->where('location', $request->get('location'))->first()->val;
		$showplan = Showplan::find($showplan_id);
		if(is_null($showplan))
			abort(404, 'Page not found');

		$position = count($showplan->items) + 1;

		$item = new ShowplanItem;
		$item->audio_id = $id;
		$item->showplan_id = $showplan_id;
		$item->position = $position;
		$item->save();

		return response()->json([
			'message' => 'success',
			'id' => $item->id,
			'title' => $item->audio->title,
			'artist' => $item->audio->artist->name,
			'length_string' => $item->audio->lengthString(),
			'censor' => $item->audio->censor
		]);
	}

	public function getRemoveShowplan(Request $request, $key, $id) {
		$item = ShowplanItem::find($id);
		if(is_null($item))
			abort(404, 'Page not found');
		$parent = $item->showplan;
		
		$item->delete();
		$parent->reposition();

		return response()->json([
			'message' => 'success',
			'id' => $id
		]);
	}

	public function getSelectShowplanItem(Request $request, $key, $id) {
		$item = ShowplanItem::find($id);
		if(is_null($item))
			abort(404, 'Page not found');

		$md5 = $item->audio->md5;
		Config::updateLocationValue($request->get('location'), 'next_on_showplan', $md5);

		return response()->json(['message' => 'success']);
	}

	public function postLog(Request $request, $key) {
		$location = $request->get('location');
		$artist = $request->get('artist');
		$title = $request->get('title');

		$log = new Log;
		$log->audioid = null;
		$log->location = $location;
		$log->userid = auth()->user()->id;
		$log->track_title = $title;
		$log->track_artist = $artist;
		$log->datetime = time();
		$log->save();

		return response()->json(['message' => 'success']);
	}

	public function getLoadShowplan(Request $request, $key, $id) {
		$location = $request->get('location');
		$showplan = Showplan::find($id);

		if(!is_null($showplan) and $showplan->canEdit(auth()->user())) {
			$studio_showplan_id = Config::where('parameter', 'default_showplan')->where('location', $location)->first()->val;
			$studio_showplan = Showplan::find($studio_showplan_id);
			$studio_showplan->items()->delete();

			$censor_start = Config::where('location', '-1')->where('parameter', 'censor_start')->first()->val;
			$censor_end = Config::where('location', '-1')->where('parameter', 'censor_end')->first()->val;
			$hour = (int) date('H');
			$censor_period = ($hour >= $censor_start && $hour < $censor_end);

			$i = 1;
			foreach($showplan->items as $item) {
				if(!$censor_period or $item->audio->censor == 'f') {
					$new_item = new ShowplanItem;
					$new_item->showplan_id = $studio_showplan_id;
					$new_item->audio_id = $item->audio_id;
					$new_item->position = $i++;
					$new_item->save();
				}
			}
		}

		return redirect()->route('studio-view', $key);
	}

	public function getReset(Request $request, $key) {
		$location = $request->get('location');

		shell_exec('/usr/scripts/restart_po' . $location . '_php');

		return response()->json([
			'status' => 'ok'
		]);
	}
}
