<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model {

	//
    public function category() {
        return $this->belongsTo('App\Category');    
    }

}
