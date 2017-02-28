<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public function characters() {
        return $this->hasMany('App\Character')->orderBy('name');
    }
}
