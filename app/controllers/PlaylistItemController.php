<?php

class PlaylistItemController extends \BaseController
{
	public function postNewPlaylistItem()
	{
		$dropId = Input::get('dropped');
		$playlistId = Input::get('pid');
		$dropType = Input::get('type');

		$lastSong = PlaylistItem::where('playlist_id','=',$playlistId)->orderBy('order','desc')->get()->first();

		if ($dropType == 'sid')
		{
			$newItem = new PlaylistItem();
			$newItem->playlist_id = $playlistId;
			$newItem->order = $lastSong['attributes']['order'] + 1;
			$newItem->song_id = $dropId;
			$newItem->save();
		}
		elseif ($dropType == 'iid')
		{
			$updateItem = PlaylistItem::find($dropId);
			$updateItem->playlist_id = $playlistId;
			$updateItem->order = $lastSong['attributes']['order'] + 1;
			$updateItem->save();
		}
		else
		{
			return Response::make('That is an invalid item to add to playlist', 400);
		}

		return Response::make('song added', 200);
	}

	public function getPlaylistItems($pid)
	{
		$uid = Auth::id();

		$songs = PlaylistItem::with('song')->where('playlist_id', '=', $pid)->orderBy('order')->get();

		return Response::json($songs);
	}
}
