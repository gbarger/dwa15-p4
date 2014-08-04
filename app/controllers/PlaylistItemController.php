<?php

class PlaylistItemController extends \BaseController
{
	/* add items to a playlist, this can either be from the library 
	 * or by moving a playlist item from another playlist, or 
	 * merging one playlist into another.
	 */
	public function postNewPlaylistItem()
	{
		$dropId = Input::get('dropped');
		$playlistId = Input::get('pid');
		$dropType = Input::get('type');

		$lastSong = PlaylistItem::where('playlist_id','=',$playlistId)->orderBy('order','desc')->get()->first();
		$orderValue = $lastSong['attributes']['order'] + 1;

		// if id type is 'sid' then add the song to the playlist
		if ($dropType == 'sid')
		{
			$newItem = new PlaylistItem();
			$newItem->playlist_id = $playlistId;
			$newItem->order = $orderValue;
			$newItem->song_id = $dropId;
			$newItem->save();
		}
		// if the type is 'iid' then add the playlist item from one playlist to the dropped playlist
		elseif ($dropType == 'iid')
		{
			$updateItem = PlaylistItem::find($dropId);
			$updateItem->playlist_id = $playlistId;
			$updateItem->order = $orderValue;
			$updateItem->save();
		}
		// if the type is 'pid' then merge one playlist into the other and delete the unneeded playlist
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
		// the type is invalid, so return a 400
		else
		{
			return Response::make('That is an invalid item to add to playlist', 400);
		}

		return Response::make('song added', 200);
	}

	// return a list of the playlist items based on the given playlist id
	public function getPlaylistItems($pid)
	{
		$uid = Auth::id();

		$songs = PlaylistItem::with('song')->where('playlist_id', '=', $pid)->orderBy('order')->get();

		return Response::json($songs);
	}
}
