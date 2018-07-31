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

    public function length() {
        return ($this->end_smpl - $this->start_smpl) / 44100;
    }

    public function length_string() {
        $length = $this->length();
        $string = '';

        $seconds = $length % 60;
        $length = floor($length / 60);
        $string = sprintf('%02d', $seconds) . 's';

        // length bigger than 0 so has minutes
        if($length > 0) {
            $minutes = $length % 60;
            $length = floor($length / 60);
            $string = $minutes . 'm ' . $string;
        }
        // length bigger than 0 so has hours
        if($length > 0)
            $string = sprintf('%02d', $length) . 'h ' . $string;

        return $string;
    }
}
