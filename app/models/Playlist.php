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
		$this->belongsToMany('Song');
	}

	public function playlistItems()
	{
		$this->hasMany('PlaylistItem');
	}
}