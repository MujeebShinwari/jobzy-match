<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeekerAppliedJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seeker_applied_jobs', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('seeker_id'); // FK
	        $table->integer('job_id'); // FK
	        $table->text('cover_letter');
	        $table->tinyInteger('is_shortlisted');
	        $table->float('match_percentage');
	        $table->float('work_style_percentage');
	        $table->float('qualification_percentage');
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
        Schema::drop('seeker_applied_jobs');
    }
}
