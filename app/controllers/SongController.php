<?php

class SongController extends \BaseController
{
	// return either the library page, or the list of songs in the library
	public function getLibrary($format = 'html')
	{
		$uid = Auth::id();
		$songs = Song::where('user_id', '=', $uid)->get();
		$plists = Playlist::where('user_id', '=', $uid)->get();

		if ($format == 'json')
		{
			return Response::json($songs);
		}
		else
		{
			return View::make('library')
				->with('songs', $songs)
				->with('playlists', $plists);
		}
	}

	/* When songs are dragged to the form, the files are posted here.
	 * Save the file, then get the id3 details and save the song to 
	 * the database with the tag details.
	 */
	public function postSongUpload()
	{
		$allowExt = array('mp3');
		$file = Input::file('file');
		$filename = $file->getClientOriginalName();
		$extension = $file->getClientOriginalExtension();
		$userid = Auth::id();
		$path = 'songs/' . $userid . '/';

		if(!File::exists($path))
		{
			File::makeDirectory($path, 0777);
		}

		if (in_array($extension, $allowExt))
		{
			if (File::exists($path . $filename))
			{
				$i = 1;
				while(true)
				{
					if (!File::exists($path . '(' . $i . ')' . $filename))
					{
						$filename = '(' . $i . ')' . $filename;
						break;
					}
					else
					{
						$i++;
					}
				}
			}

			$getID3 = new getID3;
			$fileInfo = $getID3->analyze($file);

			$song = new Song();
			$song->title = getTag($fileInfo, 'title', $filename);
			$song->artist = getTag($fileInfo, 'artist', NULL);
			$song->album = getTag($fileInfo, 'album', NULL);
			$song->year = getTag($fileInfo, 'year', NULL);
			$song->track = getTag($fileInfo, 'track', NULL);
			$song->album = getTag($fileInfo, 'album', NULL);
			$song->genre = getTag($fileInfo, 'genre', NULL);
			$song->user_id = $userid;
			$song->file_path = './' . $path . $filename;
			$song->save();

			$file->move($path, $filename);

			return Response::make('uploaded file: ' . $filename, 200);
		}
		else
		{
			File::delete($file);
			return Response::make('File type not allowed: ' . $extension, 415);
		}
	}

	// update the id3 tag values in the song database
	public function postSongUpdate()
	{
		$song = Song::find(Input::get('sid'));
		$type = Input::get('type');
		$newValue = Input::get('newValue');

		if (strpos($type,'title') !== FALSE)
			$song->title = $newValue;
		elseif (strpos($type,'artist') !== FALSE)
			$song->artist = $newValue;
		elseif (strpos($type, 'album') !== FALSE)
			$song->album = $newValue;
		elseif (strpos($type, 'year') !== FALSE)
			$song->year = $newValue;
		elseif (strpos($type, 'track') !== FALSE)
			$song->track = $newValue;
		elseif (strpos($type, 'genre') !== FALSE)
			$song->genre = $newValue;

		$song->save();

		print_r(Input::all());

		Response::make('successfully updated song: ' + $song->id, 200);
	}
}

// this function gets the data for a specific tag or returns '' if tag not found
function getTag($fileInfoArray, $tagName, $altValue)
{
	$tagValue = '';

	if (array_key_exists('tags', $fileInfoArray))
	{
		$tags = array();
		$tags2 = array();

		if (array_key_exists('id3v1', $fileInfoArray['tags']))
			$tags = $fileInfoArray['tags']['id3v1'];

		if (array_key_exists('id3v2', $fileInfoArray['tags']))
			$tags2 = $fileInfoArray['tags']['id3v2'];

		if (count($tags2) > 0 && 
			array_key_exists($tagName, $tags2) && 
			array_key_exists(0, $tags2[$tagName]) && 
			$tags2[$tagName][0] != NULL && $tags2[$tagName][0] != '')
		{
			$tagValue = $tags2[$tagName][0];
		}

		if ($tagValue == '' && count($tags) > 0 && 
			array_key_exists($tagName, $tags) && 
			array_key_exists(0, $tags[$tagName]) && 
			$tags[$tagName][0] != NULL && $tags[$tagName][0] != '')
		{
			$tagValue = $tags[$tagName][0];
		}
		
		if ($tagValue == '' && $altValue != NULL && $altValue != '')
		{
			$tagValue = $altValue;
		}
	}
	
	return $tagValue;
}