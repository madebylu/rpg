<?php namespace App;

    use Illuminate\Database\Eloquent\Model;

    class Category extends Model {

        //
        public function edge() {
            return $this->hasMany('App\Edge')->orderBy('title');
        }
        public function item() {
            return $this->hasMany('App\Item')->orderBy('title');
        }
        public function skill() {
            return $this->hasMany('App\Skill')->orderBy('title');
        }

    }
