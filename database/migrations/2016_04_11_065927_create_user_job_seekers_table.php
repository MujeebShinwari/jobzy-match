<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserJobSeekersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_job_seekers', function (Blueprint $table) {
	        $table->increments('id');
	        $table->integer('user_id'); //FK from parent table ( users )
	        $table->string('target_job_title');
	        $table->string('target_job_location');
	        $table->string('recent_job_title');
	        $table->string('recent_company');
	        $table->string('resume');
	        $table->integer('target_salary'); //FK
	        $table->integer('education_level_id'); //FK
	        $table->integer('education_degree_id'); //FK
	        $table->integer('recent_institution_attended_id'); //FK
	        $table->tinyInteger('is_block_recent_company_id');
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
        Schema::drop('user_job_seekers');
    }
}
