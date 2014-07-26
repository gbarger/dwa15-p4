<?php

class PlaylistItem extends Eloquent
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'playlistitems';

	public function playlists()
	{
		$this->hasMany('Playlist');
	}

	public function songs()
	{
		$this->hasMany('Song');
	}
}