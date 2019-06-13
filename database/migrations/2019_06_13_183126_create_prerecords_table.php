<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrerecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prerecords', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('audio_id');
            $table->string('scheduler');
            $table->integer('scheduled_time');
            $table->timestamps();

            // Add foreign key constraits
            $table->foreign('audio_id')->references('id')->on('audio');
            // $table->foreign('scheduler')->references('username')->on('users'); <- Will be fixed in future database rework
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prerecords');
    }
}
