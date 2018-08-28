<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\User;
use App\AudiowallSetPermission;
use App\AudiowallItemColour;
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

        if(Schema::hasTable('v_audiowalls'))
            DB::statement('DROP VIEW v_audiowalls');

        Schema::dropIfExists('aw_styles_props');
        Schema::table('aw_items', function (Blueprint $table){
            $table->dropColumn('style_id');
        });
        Schema::dropIfExists('aw_props');
        Schema::dropIfExists('aw_styles');
        


        if(!Schema::hasTable('aw_set_permissions')) {
            Schema::create('aw_set_permissions', function(Blueprint $table){
                $table->increments('id');
                $table->integer('set_id');
                $table->text('username');
                $table->tinyInteger('level');
                $table->timestamps();
            });
        }

        Schema::create('aw_colours', function(Blueprint $table){
            $table->increments('id');
            $table->integer('item_id');
            $table->text('name');
            $table->integer('value');
            $table->timestamps();
        });

        $items = DB::select('SELECT * FROM aw_items');
        foreach($items as $item) {
            $colour = new AudiowallItemColour;
            $colour->item_id = $item->id;
            $colour->value = 16777215;  // #FFFFFF
            $colour->name = 'ForeColourRGB';
            $colour->save();

            $colour = new AudiowallItemColour;
            $colour->item_id = $item->id;
            $colour->value = 4361162;   // #428BCA
            $colour->name = 'BackColourRGB';
            $colour->save();
        }

        // if old table exists translate old data then destroy
        if(Schema::hasTable('aw_sets_owner')) {
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
        Schema::dropIfExists('aw_sets_owner');

        DB::statement('CREATE VIEW v_audiowalls AS
            SELECT audio.md5,
                audio.filetype AS type,
                archives.localpath AS path,
                audio.start_smpl AS start,
                audio.end_smpl AS "end",
                aw_items.item,
                aw_items.text,
                aw_walls.name AS wall_name,
                aw_walls.description AS wall_desc,
                aw_walls.page,
                aw_sets.id AS set_id,
                aw_sets.description AS set_desc,
                aw_colours.name AS prop_name,
                aw_colours.value AS prop_value
               FROM audio,
                aw_items,
                aw_walls,
                aw_sets,
                aw_colours,
                archives
              WHERE aw_items.audio_id = audio.id AND aw_items.wall_id = aw_walls.id AND aw_walls.set_id = aw_sets.id AND aw_items.id = aw_colours.item_id AND audio.archive = archives.id
              ORDER BY aw_walls.id, aw_items.item;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW v_audiowalls');
        Schema::dropIfExists('aw_colours');
    }
}
