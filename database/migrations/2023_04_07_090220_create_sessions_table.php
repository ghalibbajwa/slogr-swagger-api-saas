<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('server');
            $table->string('s_name');
            $table->integer('client');
            $table->string('c_name');
            $table->integer('profile');
            $table->string('p_name');
            $table->integer('schedule');
            $table->integer('count');
            $table->integer('n_packets');
            $table->integer('p_interval');
            $table->integer('w_time');
            $table->integer('dscp');
            $table->integer('p_size');
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
        Schema::dropIfExists('sessions');
    }
}
