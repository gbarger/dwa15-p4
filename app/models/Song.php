<?php

class Song extends Eloquent
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'songs';

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function playlists()
	{
		return $this->belongsToMany('Playlist');
	}

	public function playlistItems()
	{
		return $this->hasMany('PlaylistItem');
	}
}