<?php

	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;

	class CreateSlidersTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('sliders', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('type');
				$table->string('text');
				$table->string('upper_limit_text');
				$table->string('lower_limit_text');
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
			Schema::drop('sliders');
		}
	}
