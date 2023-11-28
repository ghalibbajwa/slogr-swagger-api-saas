<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLatestAnalyticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('latest_analytics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('session_id');
            $table->float('avg_down');
            $table->float('avg_jitter');
            $table->float('avg_up');
            $table->float('avg_rtt');
            $table->float('max_down');
            $table->float('max_jitter');
            $table->float('max_up');
            $table->float('max_rtt');
            $table->float('min_down');
            $table->float('min_jitter');
            $table->float('min_rtt');
            $table->float('min_up');
            $table->integer('r_packets');
            $table->float('st_down');
            $table->float('st_up');
            $table->float('st_rtt');
            $table->integer('t_packets');
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
        Schema::dropIfExists('latest_analytics');
    }
}
