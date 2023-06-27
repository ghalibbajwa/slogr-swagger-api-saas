<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('count');
            $table->integer('n_packets');
            $table->integer('p_interval');
            $table->integer('p_size');
            $table->integer('w_time');
            $table->integer('dscp');
            $table->integer('max_loss');
            $table->integer('max_uplink');
            $table->integer('max_downlink');
            $table->integer('max_rtt');
            $table->integer('max_jitter');
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
        Schema::dropIfExists('profiles');
    }
}
