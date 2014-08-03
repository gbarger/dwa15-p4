<?php

class PlaylistController extends \BaseController
{
	public function getPlaylists()
	{
		$playlists = Playlist::where('user_id', '=', Auth::id())->get();

		return Response::json($playlists);
	}

	public function postNewPlaylist()
	{
		$name = Input::get('plistName');

		$plist = new Playlist();
		$plist->name = $name;
		$plist->user_id = Auth::id();
		$plist->save();

		return Response::make($plist->id, 200);
	}

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
