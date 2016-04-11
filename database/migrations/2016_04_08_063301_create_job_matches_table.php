<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_matches', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('job_id'); //FK
	        $table->integer('seeker_id'); //FK
	        $table->float('match_percentage');
	        $table->dateTime('match_date_time');
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
        Schema::drop('job_matches');
    }
}
