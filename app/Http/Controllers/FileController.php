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

		$used = self::directorySize($user_base);

		$directories = [];
		foreach(Storage::directories($user_base . '/' . $path) as $directory) {
			$entry = [];
			$entry['name'] = self::removeDirectory($directory);
			$entry['path'] = $path . $entry['name'];
			$entry['size'] = self::bytesToHumanReadable(self::directorySize($directory));
			$entry['icon'] = 'fa-folder-o';
			$directories[] = $entry;	
		}

		$files = [];
		foreach(Storage::files($user_base . '/' . $path) as $file) {
			$entry = [];
			$entry['name'] = self::removeDirectory($file);
			$entry['path'] = $path . $entry['name'];
			$entry['size'] = self::bytesToHumanReadable(Storage::size($file));
			$entry['upload'] = Storage::lastModified($file);
			$entry['icon'] = self::fileIcon($entry['name']);
			$files[] = $entry;
		}

		return view('files.view')->with([
			'files' => $files,
			'directories' => $directories,
			'path' => $path,
			'parent' => (strlen($path) != 0),
			'used' => self::bytesToHumanReadable($used)
		]);
	}

	public function getDownload(Request $request, $path='') {
		$user_base = self::BASE_DIRECTORY . '/' . Auth::user()->username;

		if(substr($path, 0, 1) !== '/')
			$user_base .= '/';

		return Storage::download($user_base . $path);
	}

	// Takes a string and only returns last part of URI
	// e.g. "/a/b/c/hello.txt" becomes "hello.txt"
	private function removeDirectory($string) {
		$split = explode('/', $string);
		return end($split);
	}

	private function bytesToHumanReadable($bytes) {
		$suffixes = ['B', 'kB', 'MB', 'GB', 'TB'];
		$index = 0;
		while($bytes > 1000) {
			$bytes /= 1000;
			$index += 1;
		}
		return round($bytes, 2) . ' ' . $suffixes[$index];
	}

	private function fileIcon($file) {
		$split = explode('.', $file);
		$extension = end($split);
		if(in_array($extension, ['txt', 'rtf']))
			return 'fa-file-text-o';
		if(in_array($extension, ['zip', 'tar', 'gz', '7z', 'rar']))
			return 'fa-file-archive-o';
		if(in_array($extension, ['doc', 'docx', 'odf']))
			return 'fa-file-word-o';
		if(in_array($extension, ['xls', 'xlsx', 'ods']))
			return 'fa-file-excel-o';
		if(in_array($extension, ['ppt', 'pptx', 'odp']))
			return 'fa-file-powerpoint-o';
		if(in_array($extension, ['pdf']))
			return 'fa-file-pdf-o';
		if(in_array($extension, ['wav', 'mp3', 'flac', 'aac', 'ogg']))
			return 'fa-file-audio-o';
		if(in_array($extension, ['mp4', 'avi', 'wmv', 'mov', 'flv']))
			return 'fa-file-video-o';
		if(in_array($extension, ['jpg', 'gif', 'png', 'bmp', 'svg']))
			return 'fa-file-image-o';
		return 'fa-file-o';
	}

	private function directorySize($directory) {
		$bytes = 0;
		foreach(Storage::allFiles($directory) as $file) {
			$bytes += Storage::size($file);
		}
		return $bytes;
	}
}
