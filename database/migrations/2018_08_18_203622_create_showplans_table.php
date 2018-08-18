<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShowplansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_showplans', function(Blueprint $table){
            $table->increments('id');
            $table->text('name');
            $table->text('owner');
            $table->timestamps();
        });

        Schema::create('new_showplan_items', function(Blueprint $table){
            $table->increments('id');
            $table->integer('audio_id');
            $table->integer('showplan_id');
            $table->integer('position');
            $table->timestamps();
        });

        $showplan = new App\Showplan;
        $showplan->name = 'Studio 1';
        $showplan->owner = 'cpowell';
        $showplan->save();

        $showplan = new App\Showplan;
        $showplan->name = 'Studio 2';
        $showplan->owner = 'cpowell';
        $showplan->save();

        $showplan = new App\Showplan;
        $showplan->name = 'Studio 3';
        $showplan->owner = 'cpowell';
        $showplan->save();

        $showplan = new App\Showplan;
        $showplan->name = 'Studio 4';
        $showplan->owner = 'cpowell';
        $showplan->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('new_showplans');
        Schema::dropIfExists('new_showplan_items');
    }
}
