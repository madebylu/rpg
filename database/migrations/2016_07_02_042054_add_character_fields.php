<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCharacterFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('characters', function($table){
            $table->text('notes');
            $table->integer('boons_max');
            $table->integer('boons_current');
            $table->integer('armour');
            $table->text('wounds_text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('characters', function($table){
            $table->dropColumn('notes');
            $table->dropColumn('boons_max');
            $table->dropColumn('boons_current');
            $table->dropColumn('armour');
            $table->dropColumn('wounds_text');
        });
    }
}
