<?php

class Song extends Eloquent
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'songs';

	public function playlists()
	{
		$this->belongsToMany('Playlist');
	}
}