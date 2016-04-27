<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserEmployersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_employers', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('user_id'); //FK from parent table ( users )
	        $table->integer('company_id'); //FK
	        $table->integer('division_id'); //FK
	        $table->integer('industry_id'); //FK
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
        Schema::drop('user_employers');
    }
}
