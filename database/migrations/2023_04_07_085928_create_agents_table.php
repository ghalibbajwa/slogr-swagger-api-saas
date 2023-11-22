<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('agent_code');
            $table->string('name')->nullable();
            $table->string('ipaddress')->nullable();
            $table->string('private_ip')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('location')->nullable();   
            $table->string('machine_name')->nullable();
            $table->string('arch')->nullable();
            $table->string('processor')->nullable();
            $table->string('machine')->nullable();
            $table->string('platform')->nullable();
            $table->string('Country')->nullable();
            $table->text('disks')->nullable();
            $table->text('bios_serial_numbers')->nullable();
            $table->string('Organization')->nullable();
            $table->integer('organization_id')->nullable();
            $table->string('status')->nullable();
            $table->string('os');
            $table->string('type');
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
        Schema::dropIfExists('agents');
    }
}
