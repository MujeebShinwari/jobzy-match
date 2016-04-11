<?php

	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;

	class CreateJobPaymentsTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('job_payments', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('job_id'); // FK
				$table->string('paid_amount');
				$table->date('start_date');
				$table->date('expiry_date');
				$table->integer('plan_id'); // FK
				//Here will we include transaction details like paypal id and all that
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
			Schema::drop('job_payments');
		}
	}
