<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppsTable extends Migration {

    public function up() {
        Schema::create('apps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('hash', 24)->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('apps');
    }

}
