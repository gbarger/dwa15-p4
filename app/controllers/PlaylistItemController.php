<?php

class PlaylistItemController extends \BaseController
{
	public function postNewPlaylistItem()
	{
		$songId = Input::get('sid');
		$playlistId = Input::get('pid');

		$lastSong = PlaylistItem::where('playlist_id','=',$playlistId)->orderBy('order','desc')->get()->first();

		$newItem = new PlaylistItem();
		$newItem->song_id = $songId;
		$newItem->playlist_id = $playlistId;
		$newItem->order = $lastSong['attributes']['order'] + 1;
		$newItem->save();

		return Response::make('song added', 200);
	}

	public function getPlaylistItems($pid)
	{
		$uid = Auth::id();

		$songs = PlaylistItem::with('song')->where('playlist_id', '=', $pid)->orderBy('order')->get();

		return Response::json($songs);
	}
}
