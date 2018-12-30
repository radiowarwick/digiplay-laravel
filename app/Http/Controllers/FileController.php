<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class FileController extends Controller
{
	CONST BASE_DIRECTORY = '/user_files';

	public function getFolder(Request $request, $path='') {
		// ensure anything but base url has trailing /
		if(strlen($path) != 0 and substr($path, -1) != '/')
			$path .= '/';

		$user_base = self::BASE_DIRECTORY . '/' . Auth::user()->username;

		if(!Storage::exists($user_base))
			Storage::makeDirectory($user_base);

		if(!Storage::exists($user_base . '/' . $path))
			abort(404, 'Page not found!');

		$directories = self::removeDirectories(Storage::directories($user_base . '/' . $path));
		$files = self::removeDirectories(Storage::files($user_base . '/' . $path));

		return view('files.view')->with([
			'files' => $files,
			'directories' => $directories,
			'path' => $path,
			'parent' => (strlen($path) != 0)
		]);
	}

	public function getDownload(Request $request, $path='') {
		$user_base = self::BASE_DIRECTORY . '/' . Auth::user()->username;

		if(substr($path, 0, 1) !== '/')
			$user_base .= '/';

		return Storage::download($user_base . $path);
	}

	// Takes an array of strings and returns only last part of URI
	// e.g. "/a/b/c/hello.txt" becomes "hello.txt"
	private function removeDirectories($array) {
		$modified = array();
		foreach($array as $a) {
			$split = explode("/", $a);
			$modified[] = end($split);
		}
		return $modified;
	}
}
