<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSongsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// ---create songs table---
		Schema::create('songs', function($table)
		{
			// create standard fields
			$table->increments('id');
			$table->timestamps();

			// ---create fields specific for this table---
			// relationships
			$table->integer('user_id')->unsigned()
				->references('id')->on('users')
				->onDelete('cascade');

			// create fields for song details
			$table->string('image_path');
			$table->string('title');
			$table->string('artist');
			$table->string('album');
			$table->integer('year');
			$table->integer('track');
			$table->string('genre');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// drop the songs table
		Schema::drop('songs');
	}

}
