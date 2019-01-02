<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AudiowallItem extends Model
{
    protected $table = 'aw_items';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function audio()
    {
        return $this->hasOne('\App\Audio', 'id', 'audio_id');
    }

    public function colours()
    {
        return $this->hasMany('\App\AudiowallItemColour', 'item_id', 'id');
    }
}
