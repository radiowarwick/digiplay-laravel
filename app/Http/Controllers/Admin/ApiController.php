<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

use App\ApiApplication;

class ApiController extends Controller
{
    public function getIndex(Request $request) {
        $applications = ApiApplication::all();
        return view('admin.api.index')->with([
            'applications' => $applications
        ]);
    }

    public function postCreate(Request $request) {
        $request->validate([
            'name' => 'required'
        ]);

        $application = new ApiApplication;
        $application->name = $request->input('name');
        $application->key = $this->generateKey();
        $application->save();

        return redirect()->back();
    }

    public function getDelete(Request $request, $id) {
        $application = ApiApplication::findOrFail($id);
        $application->delete();
        return redirect()->back();
    }

    // Generate a key which is unique
    private function generateKey() {
        do {
            $key = Str::random(32);
        } while(ApiApplication::where('key', $key)->count() > 0);
        return $key;
    }
}
