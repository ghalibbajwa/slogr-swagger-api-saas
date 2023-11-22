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
            $table->float('count');
            $table->float('n_packets');
            $table->float('p_interval');
            $table->float('p_size');
            $table->float('w_time');
            $table->float('dscp');
            $table->float('rtt-g');
            $table->float('rtt-r');
            $table->float('uplink-g');
            $table->float('uplink-r');
            $table->float('downlink-g');
            $table->float('downlink-r');
            $table->float('delay-g');
            $table->float('delay-r');
            $table->float('downlink-bw-g');
            $table->float('downlink-bw-r');
            $table->float('uplink-bw-g');
            $table->float('uplink-bw-r');
            $table->float('jitter-g');
            $table->float('jitter-r');
            $table->float('loss-g');
            $table->float('loss-r');

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
