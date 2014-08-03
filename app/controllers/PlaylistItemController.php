<?php

class PlaylistItemController extends \BaseController
{
	public function postNewPlaylistItem()
	{
		$dropId = Input::get('dropped');
		$playlistId = Input::get('pid');
		$dropType = Input::get('type');

		$lastSong = PlaylistItem::where('playlist_id','=',$playlistId)->orderBy('order','desc')->get()->first();
		$orderValue = $lastSong['attributes']['order'] + 1;

		if ($dropType == 'sid')
		{
			$newItem = new PlaylistItem();
			$newItem->playlist_id = $playlistId;
			$newItem->order = $orderValue;
			$newItem->song_id = $dropId;
			$newItem->save();
		}
		elseif ($dropType == 'iid')
		{
			$updateItem = PlaylistItem::find($dropId);
			$updateItem->playlist_id = $playlistId;
			$updateItem->order = $orderValue;
			$updateItem->save();
		}
		elseif ($dropType == 'pid')
		{
			$moveItems = PlaylistItem::where('playlist_id', '=', $dropId)->orderBy('order')->get();

			foreach ($moveItems as $pi)
			{
				$pi->playlist_id = $playlistId;
				$pi->order = $orderValue;
				$pi->save();

				$orderValue++;
			}

			$plist = Playlist::where('id', '=', $dropId)->delete();
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
