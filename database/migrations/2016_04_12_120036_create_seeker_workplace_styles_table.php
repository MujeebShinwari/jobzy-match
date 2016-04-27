<?php

	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;

	class CreateSeekerWorkplaceStylesTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('seeker_workplace_styles', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('slider_id'); // FK
				$table->integer('seeker_id'); // FK
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
			Schema::drop('seeker_workplace_styles');
		}
	}
