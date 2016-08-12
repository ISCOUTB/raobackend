<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlarmsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('alarms', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('STUDENTID', 10);
            $table->string('PERIODO', 6);
            $table->integer('failedattendance');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('alarms');
    }

}
