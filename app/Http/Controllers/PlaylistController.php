<?php

namespace App\Http\Controllers;

use App\Playlist;
use App\PlaylistAudio;
use App\PlaylistColour;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:Playlist editor');
    }

    public function getIndex(Request $request)
    {
        $studioPlaylists = Playlist::studio()->get();
        $sustainerPlaylists = Playlist::sustainer()->get();

        return view('playlist.index')->with([
            'studio' => $studioPlaylists,
            'sustainer' => $sustainerPlaylists,
        ]);
    }

    public function getView(Request $request, $id)
    {
        $playlist = Playlist::where('id', $id)->first();
        if ($playlist == null) {
            abort(404, 'Page not found');
        }

        $playlistAudio = $playlist->playlistAudio()->paginate(25)->appends($_GET);

        return view('playlist.view')->with([
            'playlist' => $playlist,
            'playlistAudio' => $playlistAudio,
        ]);
    }

    public function getCreate(Request $request)
    {
        return view('playlist.create');
    }

    public function getEdit(Request $request, $id)
    {
        $playlist = Playlist::where('id', $id)->first();
        if ($playlist == null) {
            abort(404, 'Page not found');
        }

        return view('playlist.edit')->with([
            'playlist' => $playlist,
        ]);
    }

    public function postRemove(Request $request)
    {
        $playlistAudio = PlaylistAudio::where('id', $request->get('id'));
        if ($playlistAudio == null) {
            return response()->json([
                'status' => 'error',
            ]);
        }

        $playlistAudio->delete();

        return response()->json([
            'status' => 'ok',
        ]);
    }

    public function postUpdate(Request $request)
    {
        if ($request->get('remove') == 'true') {
            $playlistAudio = PlaylistAudio::where('audioid', $request->get('audio_id'))->where('playlistid', $request->get('playlist_id'))->first();
            $playlistAudio->delete();

            return response()->json([
                'removed' => 'true',
            ]);
        } else {
            $playlistAudio = new PlaylistAudio;
            $playlistAudio->playlistid = $request->get('playlist_id');
            $playlistAudio->audioid = $request->get('audio_id');
            $playlistAudio->save();

            return response()->json([
                'removed' => 'false',
            ]);
        }
    }

    public function postCreate(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $playlist = new Playlist;
        $playlist->name = $request->get('name');
        if ($request->get('sustainer') == null) {
            $playlist->sustainer = 'f';
        } else {
            $playlist->sustainer = 't';
        }
        $playlist->save();

        $colour = new PlaylistColour;
        $colour->playlistid = $playlist->id;
        $colour->colour = str_replace('#', '', $request->get('colour'));
        $colour->save();

        return redirect()->route('playlist-index');
    }

    public function postEdit(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $playlist = Playlist::where('id', $id)->first();
        if ($playlist == null) {
            abort(404, 'Page not found');
        }

        $playlist->name = $request->get('name');
        if ($request->get('sustainer') == null) {
            $playlist->sustainer = 'f';
        } else {
            $playlist->sustainer = 't';
        }
        $playlist->save();

        $colour = PlaylistColour::where('playlistid', $id)->first();
        if ($colour == null) {
            $colour = new PlaylistColour;
            $colour->playlistid = $id;
        }
        $colour->colour = str_replace('#', '', $request->get('colour'));
        $colour->save();

        return redirect()->route('playlist-view', $playlist->id);
    }
}
