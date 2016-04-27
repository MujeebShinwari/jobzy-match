<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyCulturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_cultures', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('slider_id'); // FK
	        $table->integer('employer_id'); // FK
	        $table->integer('company_id'); // FK
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
        Schema::drop('company_cultures');
    }
}
