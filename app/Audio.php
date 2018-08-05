<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{
    protected $table = 'audio';
    protected $primaryKey = 'id';
    
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = null;
    public function audioArtist() {
    	return $this->hasOne('App\AudioArtist', 'audioid');
    }

    public function artist() {
        return $this->audioArtist->artist();
    }

    public function scopeTracks($query) {
    	return $query->where('type', 1);
    }

    /*
    *   Params is an associative array. Indexes are:
    *   "query"     =>  (string) name to search
    *   "type"      =>  (array) types to search, as string, Song, Prerec, Jingle, Advert
    *   "filter"    =>  (array) types of filter, as string, title, artist, album
    */
    public function scopeSearch($query, $params) {
        $allowed_types = [null, 'Music', 'Jingle', 'Advert', 'Prerec'];
        $filtered_types = [];

        // Fill filtered types with index of each allowed type
        foreach($params['type'] as $type) {
            if(in_array($type, $allowed_types))
                $filtered_types[] = array_search($type, $allowed_types);
        }

        $query->where(function($query) use (&$filtered_types){
            foreach($filtered_types as $type) {
                $query->orWhere('type', $type);
            }
        });

        $strip_query = '%' . trim($params['query']) . '%';
        $filters = $params['filter'];

        // setup filter joins
        foreach($filters as $filter) {
            if($filter == 'artist') {
                $query->join('audioartists', 'audio.id', '=', 'audioartists.audioid')
                    ->join('artists', 'audioartists.artistid', '=', 'artists.id');
            }
            else if($filter == 'album') {
                $query->join('albums', 'audio.music_album', '=', 'albums.id');
            }
        }

        // do filter wheres
        $query->where(function($query) use (&$filters, &$strip_query){
            foreach($filters as $filter) {
                if($filter == 'title') {
                    $query->orWhere('title', 'ILIKE', $strip_query);
                }
                else if($filter == 'artist') {
                    $query->orWhere('artists.name', 'ILIKE', $strip_query);
                }
                else if($filter == 'album') {
                    $query->orWhere('albums.name', 'ILIKE', $strip_query);
                }
            }
        });

        return $query->orderBy('audio.id', 'DESC');
    }
}
