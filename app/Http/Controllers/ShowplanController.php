<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Showplan;
use App\ShowplanItem;
use App\ShowplanPermission;
use App\Audio;

class ShowplanController extends Controller
{
	public function getIndex(Request $request) {
		$showplans = auth()->user()->showplans();

		$user_showplan_count = count(ShowplanPermission::where('username', auth()->user()->username)->where('level', 2)->get());
		$can_create = ($user_showplan_count < 5 or auth()->user()->hasPermission('Showplan admin'));

		return view('showplan.index')->with('showplans', $showplans)->with('can_create', $can_create);
	}

	public function getEdit(Request $request, $id) {
		$showplan = Showplan::find($id);
		if(is_null($showplan))
			abort(404, 'Page not found');
		else if(!$showplan->canEdit(auth()->user()))
			abort(403, 'Not authorised');

		return view('showplan.edit')->with('showplan', $showplan);
	}

	public function getSwapItems(Request $request, $id, $first_id, $second_id) {
		$showplan = Showplan::find($id);
		if(is_null($showplan))
			abort(404, 'Page not found');
		else if(!$showplan->canEdit(auth()->user()))
			abort(403, 'Not authorised');

		$first_item = ShowplanItem::find($first_id);
		$second_item = ShowplanItem::find($second_id);
		if(is_null($first_item) or is_null($second_item))
			abort(404, 'Page not found');
		if($first_item->showplan->id != $id or $second_item->showplan->id != $id)
			abort(403, 'Not authorised');

		$first_position = $first_item->position;
		$first_item->position = $second_item->position;
		$second_item->position = $first_position;

		$first_item->save();
		$second_item->save();

		return response()->json(['message' => 'success']);
	}

	public function getRemoveItem(Request $request, $id, $item_id) {
		$showplan = Showplan::find($id);
		if(is_null($showplan))
			abort(404, 'Page not found');
		else if(!$showplan->canEdit(auth()->user()))
			abort(403, 'Not authorised');

		$item = ShowplanItem::find($item_id);
		if(is_null($item))
			abort(404, 'Page not found');
		if($item->showplan->id != $id)
			abort(403, 'Not authorised');

		$item->delete();
		$showplan->reposition();

		return response()->json(['message' => 'success']);
	}

	public function getAddItem(Request $request, $id, $audio_id) {
		$showplan = Showplan::find($id);
		$audio = Audio::find($audio_id);
		if(is_null($showplan) or is_null($audio))
			abort(404, 'Page not found');
		else if(!$showplan->canEdit(auth()->user()))
			abort(403, 'Not authorised');

		$item = new ShowplanItem;
		$item->audio_id = $audio_id;
		$item->showplan_id = $id;
		$item->position = 9999;
		$item->save();

		$showplan->reposition();

		return response()->json([
			'message' => 'success',
			'audio' => [
				'title' => $audio->title,
				'artist' => $audio->artist->name,
				'album' => $audio->album->name,
				'length' => $audio->lengthString(),
				'item' => $item->id,
				'censor' => $audio->censor
		]]);
	}

	public function getDelete(Request $request, $id) {
		$showplan = Showplan::find($id);
		if(is_null($showplan))
			abort(404, 'Page not found');
		else if(!$showplan->isOwner(auth()->user()) or $showplan->id <= 4)
			abort(403, 'Not authorised');

		return view('showplan.delete')->with('showplan', $showplan);
	}

	public function getDeleteYes(Request $request, $id) {
		$showplan = Showplan::find($id);
		if(is_null($showplan))
			abort(404, 'Page not found');
		else if(!$showplan->isOwner(auth()->user()) or $showplan->id <= 4)
			abort(403, 'Not authorised');

		$showplan->permissions()->delete();
		$showplan->items()->delete();
		$showplan->delete();

		return redirect()->route('showplan-index');
	}

	public function getSettings(Request $request, $id) {
		$showplan = Showplan::find($id);
		if(is_null($showplan))
			abort(404, 'Page not found');
		else if(!$showplan->isOwner(auth()->user()))
			abort(403, 'Not authorised');

		return view('showplan.settings')->with('showplan', $showplan);
	}

	public function postCreate(Request $request) {
		$request->validate([
			'name' => 'required'
		]);

		$user_showplan_count = count(ShowplanPermission::where('username', auth()->user()->username)->where('level', 2)->get());

		if($user_showplan_count < 5 or auth()->user()->hasPermission('Showplan admin')) {
			$showplan = new Showplan;
			$showplan->name = $request->get('name');
			$showplan->save();

			$permission = new ShowplanPermission;
			$permission->level = 2;
			$permission->username = auth()->user()->username;
			$permission->showplan_id = $showplan->id;
			$permission->save();

			return redirect()->route('showplan-edit', $showplan->id);
		}
		else
			return redirect()->back()->withErrors(['You may only have 5 showplans at a time']);
	}

	public function postSettingName(Request $request, $id) {
		$request->validate([
			'name' => 'required'
		]);

		$showplan = Showplan::find($id);
		if(is_null($showplan))
			abort(404, 'Page not found');
		else if(!$showplan->isOwner(auth()->user()))
			abort(403, 'Not authorised');

		$showplan->name = $request->get('name');
		$showplan->save();

		return redirect()->back();
	}

	public function postSettingAdd(Request $request, $id) {
		$request->validate([
			'username' => [
				'required',
				'exists:users',
				Rule::unique('showplan_permissions')->where(function($query) use (&$id){
					return $query->where('showplan_id', $id);
				})
			],
		]);

		$showplan = Showplan::find($id);
		if(is_null($showplan))
			abort(404, 'Page not found');
		else if(!$showplan->isOwner(auth()->user()))
			abort(403, 'Not authorised');

		$permission = new ShowplanPermission;
		$permission->username = $request->get('username');
		$permission->level = 1;
		$permission->showplan_id = $id;
		$permission->save();

		return redirect()->back();
	}

	public function getSettingRemove(Request $request, $id, $username) {
		$showplan = Showplan::find($id);
		if(is_null($showplan))
			abort(404, 'Page not found');
		else if(!$showplan->isOwner(auth()->user()))
			abort(403, 'Not authorised');

		$permission = ShowplanPermission::where('username', $username)->where('showplan_id', $id)->where('level', 1)->first();
		$permission->delete();

		return redirect()->back();
	}
}