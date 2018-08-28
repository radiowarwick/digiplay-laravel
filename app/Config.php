<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = 'configuration';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public static function getLocationByKey($key) {
    	$config = Config::where('parameter', 'security_key')->where('val', $key)->first();
    	if(is_null($config))
    		return null;
    	return $config->location;
    }

    public static function updateLocationValue($location, $parameter, $value) {
        $config = Config::where('location', $location)->where('parameter', $parameter)->first();
        $config->val = $value;
        $config->save();
    }

    public function scopeLocation($query, $key) {
    	return $query->where('location', $key);
    }
}
