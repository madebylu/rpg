<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Character extends Model {

    public function background() {
        return $this->belongsToMany('App\Background')->orderBy('title');
    }

    public function edge() {
        return $this->belongsToMany('App\Edge')->orderBy('title');
    }

	public function heritage() {
        return $this->belongsTo('App\Heritage')->orderBy('title');
    }

    public function skill() {
        return $this->belongsToMany('App\Skill')->withPivot('points', 'bonus')->orderBy('title');
    }

}
