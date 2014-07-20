<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaylistItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// ---create playlist items table---
		Schema::create('playlist_items', function($table)
		{
			// create standard fields
			$table->increments('id');
			$table->timestamps();

			// ---create fields specific for this table---
			// relationships
			$table->integer('playlist_id')
				->references('id')->on('playlists')
				->onDelete('cascade');

			$table->integer('song_id')
				->references('id')->on('songs')
				->onDelete('cascade');;
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
		Schema::drop('playlist_items');
	}

}
