<?php

	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;

	class CreateJobsTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('jobs', function (Blueprint $table) {
				$table->increments('id');
				$table->tinyInteger('type'); // 1 => Job / 2 => Screen
				$table->string('title');
				$table->text('desc');
				$table->string('city');
				$table->string('state');
				$table->integer('zip');
				$table->integer('employer_id'); //FK
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
			Schema::drop('jobs');
		}
	}