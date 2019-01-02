<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{
    protected $table = 'audio';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function audioArtist()
    {
        return $this->hasOne('App\AudioArtist', 'audioid', 'id');
    }

    public function artist()
    {
        return $this->audioArtist->artist();
    }

    public function album()
    {
        return $this->hasOne('App\Album', 'id', 'music_album');
    }

    public function audioDir()
    {
        return $this->hasOne('App\AudioDir', 'audioid', 'id');
    }

    // annoying name to not share column name
    public function theArchive()
    {
        return $this->hasOne('App\Archive', 'id', 'archive');
    }

    public function filePath()
    {
        if ($this->archive !== 0) {
            $folder = substr($this->md5, 0, 1);

            return $this->theArchive->localpath.'/'.$folder.'/'.$this->md5.'.flac';
        }
    }

    public function scopeTracks($query)
    {
        return $query->where('type', 1);
    }

    public function length()
    {
        return ($this->end_smpl - $this->start_smpl) / 44100;
    }

    public function lengthString()
    {
        $length = $this->length();
        $string = '';

        $seconds = $length % 60;
        $length = floor($length / 60);
        $string = sprintf('%02d', $seconds).'s';

        // length bigger than 0 so has minutes
        if ($length > 0) {
            $minutes = $length % 60;
            $length = floor($length / 60);
            $string = $minutes.'m '.$string;
        }
        // length bigger than 0 so has hours
        if ($length > 0) {
            $string = sprintf('%02d', $length).'h '.$string;
        }

        return $string;
    }

    /*
    *   Params is an associative array. Indexes are:
    *   "query"     =>  (string) name to search
    *   "type"      =>  (array) types to search, as string, Song, Prerec, Jingle, Advert
    *   "filter"    =>  (array) types of filter, as string, title, artist, album
    *   "censor"    =>  (boolean) true to include censored tracks
    */
    public function scopeSearch($query, $params)
    {
        $allowed_types = [null, 'Music', 'Jingle', 'Advert'];
        $filtered_types = [];

        // If the user has permissions, Prerec is an allowed type
        if (auth()->user()->hasPermission('Sustainer admin')) {
            array_push($allowed_types, 'Prerec');
        }

        // Fill filtered types with index of each allowed type
        foreach ($params['type'] as $type) {
            if (in_array($type, $allowed_types)) {
                $filtered_types[] = array_search($type, $allowed_types);
            }
        }

        $query->where(function ($query) use (&$filtered_types) {
            foreach ($filtered_types as $type) {
                $query->orWhere('type', $type);
            }
        });

        // setup filter joins
        $filter_columns = [];
        foreach ($params['filter'] as $filter) {
            if ($filter == 'artist') {
                $query->join('audioartists', 'audio.id', '=', 'audioartists.audioid')
                    ->join('artists', 'audioartists.artistid', '=', 'artists.id');
                $filter_columns[] = '"artists"."name"';
            } elseif ($filter == 'album') {
                $query->join('albums', 'audio.music_album', '=', 'albums.id');
                $filter_columns[] = '"albums"."name"';
            } elseif ($filter == 'title') {
                $filter_columns[] = '"title"';
            }
        }

        // must not be in the bin to be in results
        $query->join('audiodir', 'audio.id', '=', 'audiodir.audioid');
        $query->where('audiodir.dirid', 2);

        // search filter
        $ts_vector = implode(' || \' \' || ', $filter_columns);
        $search_term = pg_escape_string($params['query']);

        // good searching of all columns to get best matches
        $query->whereRaw('to_tsvector('.$ts_vector.')::tsvector @@ plainto_tsquery(\'english\', \''.$search_term.'\')::tsquery');

        // Apply filter if param is not set or (if set) value is not "false"
        if (! (isset($params['censor']) and $params['censor'] == 'false')) {
            $query->where('censor', 'f');
        }

        return $query->orderBy('audio.id', 'DESC')->select('audio.*');
    }

    public function setAlbum($album_str)
    {
        $album = Album::where('name', $album_str)->first();
        if (! isset($album)) {
            $album = new Album;
            $album->name = $album_str;
            $album->save();
        }

        $this->music_album = $album->id;
        $this->save();
    }

    public function setArtist($artist_str)
    {
        $artist = Artist::where('name', $artist_str)->first();
        if (! isset($artist)) {
            $artist = new Artist;
            $artist->name = $artist_str;
            $artist->save();
        }

        $audio_artist = AudioArtist::where('audioid', $this->id)->first();
        if (! isset($audio_artist)) {
            $audio_artist = new AudioArtist;
            $audio_artist->audioid = $this->id;
        }

        $audio_artist->artistid = $artist->id;
        $audio_artist->save();
    }

    public function getTypeString()
    {
        $types = ['Music', 'Jingle', 'Advert', 'Prerec'];

        return $types[$this->type - 1];
    }

    public function getVocalIn()
    {
        return $this->vocal_start / 44100;
    }

    public function getVocalOut()
    {
        return $this->vocal_end / 44100;
    }

    public function moveToBin()
    {
        $audioDir = $this->audioDir;
        $audioDir->dirid = 3;
        $audioDir->save();
    }

    public function fetchFromBin()
    {
        $audioDir = $this->audioDir;
        $audioDir->dirid = 2;
        $audioDir->save();
    }
}
