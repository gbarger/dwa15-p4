<?php

class CrossObjectController extends \BaseController
{
	// delete the given item
	public function postDelete()
	{
		$delType = Input::get('type');
		$delId = Input::get('id');

		// if the type is a song, delete the file and the song
		if ($delType == 'song')
		{
			PlaylistItem::where('song_id', '=', $delId)->delete();
			$song = Song::find($delId);
			$filePath = substr($song->file_path,2);
			File::delete($filePath);
			$song->delete();
		}
		// if the type is playlist, delete teh playlist
		elseif ($delType == 'playlist')
		{
			PlaylistItem::where('playlist_id', '=', $delId)->delete();
			Playlist::find($delId)->delete();
		}
		// if the type is playlist item delete the given playlist item
		elseif ($delType == 'playlistItem')
		{
			PlaylistItem::find($delId)->delete();
		}

		return Response::make('deleted ' . $delType . ': ' . $delId, 200);
	}
}