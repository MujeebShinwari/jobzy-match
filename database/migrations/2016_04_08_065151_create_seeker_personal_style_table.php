<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeekerPersonalStyleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seeker_personal_style', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('slider_id'); // FK
	        $table->integer('seeker_id'); // FK
	        $table->string('score');
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
        Schema::drop('seeker_personal_style');
    }
}
