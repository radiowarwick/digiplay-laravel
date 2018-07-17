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
}
