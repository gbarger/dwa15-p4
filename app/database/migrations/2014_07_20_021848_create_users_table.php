<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create the users table and fields
		// ---create playlist items table---
		Schema::create('users', function($table)
		{
			// create standard fields
			$table->increments('id');
			$table->timestamps();

			// ---create fields specific for this table---
			$table->string('username');
			$table->string('password');
			$table->string('remember_token');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// drop the playlist items table
		Schema::drop('users');
	}

}
