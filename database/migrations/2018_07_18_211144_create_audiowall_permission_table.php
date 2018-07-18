<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAudiowallPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // remove old tables
        // Schema::dropIfExists('aw_sets_permissions');
        // Schema::dropIfExists('aw_sets_owner');
        // Schema::dropIfExists('aw_props');
        // Schema::dropIfExists('aw_styles');
        // Schema::dropIfExists('aw_styles_props');

        Schema::create('aw_set_permissions', function(Blueprint $table){
            $table->increments('id');
            $table->integer('set_id');
            $table->text('username');
            $table->tinyInteger('level');
            $table->timestamps();
        });

        Schema::create('aw_colours', function(Blueprint $table){
            $table->increments('id');
            $table->integer('item_id');
            $table->text('name');
            $table->integer('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aw_set_permissions');
        Schema::dropIfExists('aw_colours');
    }
}
