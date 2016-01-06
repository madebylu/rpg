<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Character extends Model {

    public function background() {
        return $this->belongsToMany('App\Background');
    }

    public function edge() {
        return $this->belongsToMany('App\Edge');
    }

	public function heritage() {
        return $this->belongsTo('App\Heritage');
    }

    public function skill() {
        return $this->belongsToMany('App\Skill')->withPivot('points', 'bonus');
    }

}
