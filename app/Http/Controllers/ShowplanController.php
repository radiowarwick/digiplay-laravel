<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Showplan;

class ShowplanController extends Controller
{
	public function getIndex(Request $request) {
		$showplans = Showplan::all();

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
}
