<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParsesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('parses', function($table){
			$table -> increments('id');
			$table -> string('title', 150);
			$table -> string('company', 150);
			$table -> string('salary', 150);
			$table -> string('city', 150);
			$table -> string('experience', 150);
			$table -> text('description');
			$table -> string('type_of_job', 150);
			$table -> string('address', 150);
			$table -> string('date_of_publicity', 150);
			$table -> timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('parses');
	}

}
