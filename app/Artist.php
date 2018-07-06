<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $table = 'artists';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function audioArtists() {
    	return $this->belongsTo('AudioArtist', 'artistid');
    }
}
