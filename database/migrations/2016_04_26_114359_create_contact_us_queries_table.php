<?php

	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;

	class CreateContactUsQueriesTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('contact_us_queries', function (Blueprint $table) {
				$table->increments('id');
				$table->string('name');
				$table->string('email');
				$table->string('phone');
				$table->string('message');
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
			Schema::drop('contact_us_queries');
		}
	}
