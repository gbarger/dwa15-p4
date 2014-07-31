<?php

class PlaylistController extends \BaseController
{
	public function getPlaylists()
	{
		$playlists = Playlist::where('user_id', '=', Auth::id());

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
}
