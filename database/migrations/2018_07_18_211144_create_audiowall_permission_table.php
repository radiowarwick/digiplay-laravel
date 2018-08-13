<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\User;
use App\AudiowallSetPermission;
use Illuminate\Support\Facades\DB;

class CreateAudiowallPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('aw_sets_permissions');
        Schema::dropIfExists('aw_sets_owner');

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

        $owners = DB::select('SELECT * FROM aw_sets_owner');
        foreach($owners as $owner) {
            $user = User::where('id', $owner->user_id)->first();

            $new_permission = new AudiowallSetPermission;

            $new_permission->username = $user->username;
            $new_permission->set_id = $owner->set_id;
            $new_permission->level = 4;

            $new_permission->save();
        }
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
