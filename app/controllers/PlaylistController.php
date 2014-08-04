<?php

class PlaylistController extends \BaseController
{
	// get a list of the playlists for the logged in user
	public function getPlaylists()
	{
		$playlists = Playlist::where('user_id', '=', Auth::id())->get();

		return Response::json($playlists);
	}

	// create a new playlist
	public function postNewPlaylist()
	{
		$name = Input::get('plistName');

		$plist = new Playlist();
		$plist->name = $name;
		$plist->user_id = Auth::id();
		$plist->save();

		return Response::make($plist->id, 200);
	}

	// edit the playlist name
	public function postEditPlaylist()
	{
		$pid = Input::get('pid');
		$newValue = Input::get('newValue');

		$playlist = Playlist::find($pid);
		$playlist->name = $newValue;
		$playlist->save();
		
		return Response::make('updated the playlist: ' + $pid, 200);
	}
}
