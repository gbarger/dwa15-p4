<?php

class PlaylistItem extends Eloquent
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'playlistitems';

	public function playlist()
	{
		return $this->belongsTo('Playlist');
	}

	public function song()
	{
		return $this->belongsTo('Song');
	}
}