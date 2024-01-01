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
            $table->float('rtt_g');
            $table->float('rtt_r');
            $table->float('uplink_g');
            $table->float('uplink_r');
            $table->float('downlink_g');
            $table->float('downlink_r');
            $table->float('delay_g');
            $table->float('delay_r');
            $table->float('downlink_bw_g');
            $table->float('downlink_bw_r');
            $table->float('uplink_bw_g');
            $table->float('uplink_bw_r');
            $table->float('jitter_g');
            $table->float('jitter_r');
            $table->float('loss_g');
            $table->float('loss_r');
            $table->integer('organization_id')->nullable();
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
