<?php

	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;

	class CreateSeekerMatchedCapabilitiesTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('seeker_matched_capabilities', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('seeker_id'); //FK
				$table->integer('capability_id'); //FK
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
			Schema::drop('seeker_matched_capabilities');
		}
	}
