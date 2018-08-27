<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Showplan;
use App\ShowplanItem;
use App\ShowplanPermission;

class ShowplanController extends Controller
{
	public function getIndex(Request $request) {
		$showplans = auth()->user()->showplans();

		return view('showplan.index')->with('showplans', $showplans);
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

	function getRemoveItem(Request $request, $id, $item_id) {
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
}
