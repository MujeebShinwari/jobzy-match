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
	    //FK -> Foreign key
        Schema::create('user_job_seekers', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('user_id'); //FK from parent table ( users )
	        $table->string('target_job_title');
	        $table->string('target_job_location');
	        $table->integer('recent_job_title_id');//FK
	        $table->string('recent_company');
	        $table->string('resume');
	        $table->integer('target_salary');
	        $table->integer('education_level_id');//FK
	        $table->integer('education_id');//FK
	        $table->integer('recent_institution_attended_id');//FK
	        $table->integer('is_block_recent_company_id'); //FK
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
