<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Config;

class ConfigurationController extends Controller
{
    public function getConfiguration(Request $request) {
        $config = Config::select();
        
        if($request->get('location')) {
            $config->where('location', $request->get('location'));
        }
        if($request->get('parameter')) {
            $config->where('parameter', $request->get('parameter'));
        }

        return response()->json($config->get());
    }
}
