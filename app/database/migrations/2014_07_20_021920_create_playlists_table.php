<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaylistsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// ---create playlists table---
		Schema::create('playlists', function($table)
		{
			// create standard fields
			$table->increments('id');
			$table->timestamps();

			// ---create fields specific for this table---
			// relationships
			$table->integer('user_id')->unsigned()
				->references('id')->on('users')
				->onDelete('cascade');

			// create fields for playlist details
			$table->string('name');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// drop the playlists table
		Schema::drop('playlists');
	}

}
