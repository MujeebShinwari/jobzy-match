<?php

	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;

	class CreateSeekerResumeMatchesTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('seeker_resume_matches', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('seeker_id'); //FK
				$table->integer('skill_match_count'); //FK
				$table->integer('capability_match_count'); //FK
				$table->integer('certificate_match_count'); //FK
				$table->integer('leadership_match_count'); //FK
				$table->integer('institute_match_count'); //FK
				$table->integer('company_match_count'); //FK
				$table->integer('extracurricular_match_count'); //FK
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
			Schema::drop('seeker_resume_matches');
		}
	}
