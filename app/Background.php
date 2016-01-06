<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Background extends Model {

	public function character(){
        return $this->belongsToMany('App\Character');
    }

}
