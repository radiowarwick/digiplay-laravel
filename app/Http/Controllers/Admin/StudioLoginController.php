<?php

namespace App\Http\Controllers\Admin;

use App\StudioLogin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudioLoginController extends Controller
{
    public function getIndex(Request $request)
    {
        $now = date('Y-m-d');
        $logins = StudioLogin::onDate($now)->where('location', 1)->get();

        return view('admin.studio.index')->with('time', $now)->with('logins', $logins)->with('location', 1);
    }

    public function postIndex(Request $request)
    {
        $time = $request->get('date');
        $location = $request->get('location');
        $logins = StudioLogin::onDate($time)->where('location', $location)->get();

        return view('admin.studio.index')->with('time', $time)->with('logins', $logins)->with('location', $location);
    }
}
