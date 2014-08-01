<?php

class CrossObjectController extends \BaseController
{
	public function postDelete()
	{
		$delType = Input::get('type');
		$delId = Input::get('id');

		if ($delType == 'song')
		{
			PlaylistItem::where('song_id', '=', $delId)->delete();
			$song = Song::find($delId);
			$filePath = substr($song->file_path,2);
			File::delete($filePath);
			$song->delete();
		}
		elseif ($delType == 'playlist')
		{
			PlaylistItem::where('playlist_id', '=', $delId)->delete();
			Playlist::find($delId)->delete();
		}
		elseif ($delType == 'playlistItem')
		{
			PlaylistItem::find($delId)->delete();
		}

		return Response::make('deleted ' . $delType . ': ' . $delId, 200);
	}

	public function getErrorPage($exception)
	{
		return Response::make('404 Page not found', 404);
	}
}