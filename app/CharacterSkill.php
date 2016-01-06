<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CharacterSkill extends Model {

	//
	protected $table = 'CharacterSkill';

	protected $appends = ['Total'];

	public function getTotalAttribute() {
		return $this->points + $this->bonus;
	}

}
