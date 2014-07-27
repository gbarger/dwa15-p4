<?php

class Playlist extends Eloquent { 

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'playlists';
	
	public function songs()
	{
		return $this->belongsToMany('Song');
	}

	public function playlistItems()
	{
		return $this->hasMany('PlaylistItem');
	}
}