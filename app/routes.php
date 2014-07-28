<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

Route::get('/login', 
	array
	(
		'before' => 'guest',
		function() 
		{
			return View::make('login');
		}
	)
);

Route::post('/login', 
	array
	(
		'before' => 'csrf',
		function() 
		{
			$cred = Input::only('email','password');
			$rem = Input::get('remember');

			if (Auth::attempt($cred, $remember = $rem))
			{
				return Redirect::intended('/library');
			}
			else
			{
				$errors = array();
				$errors[] = 'Your login attempt was incorrect';

				return Redirect::to('/login')->with('errors', $errors);
			}
		}
	)
);

Route::get('/logout', 
	function()
	{
		Auth::logout();

		return Redirect::to('/');
	}
);

Route::get('/signup', 
	array
	(
		'before' => 'guest',
		function()
		{
			return View::make('signup');
		}
	)
);

Route::post('/signup', 
	array
	(
		'before' => 'csrf',
		function()
		{
			$errors = array();
			$em = Input::get('email');
			$pw = Input::get('password');
			$pw2 = Input::get('confirm');
			$rem = Input::get('remember');

			if ($em == '')
				$errors[] = 'Email must not be blank.';

			if ($pw == '')
				$errors[] = 'Password must not be blank.';

			if ($pw2 == '')
				$errors[] = 'Password confirmation must be completed.';

			if ($pw != $pw2)
				$errors[] = 'Password and confirmation must match.';

			if (count($errors) > 0)
			{
				return View::make('signup')->with('errors', $errors);
			}
			else
			{
				$user = new User;
				$user->email = $em;
				$user->password = Hash::make($pw);

				try
				{
					$user->save();
				}
				catch(Exception $e)
				{
					$errors = array('There was an error saving the user: ' . $e);
					return View::make('signup')->with('errors', $errors);
				}

				Auth::login($user, $remember = $rem);

				return View::make('hello');
			}
		}
	)
);

Route::get('/library/{format?}', 
	array
	(
		'before' => 'auth',
		function($format = 'html')
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
	)
);

Route::post('/library', function()
{
	// post updates to the library (songs database)
});

Route::get('/playlists', function()
{
	// return playlist details for page
});

Route::post('/playlist', function()
{
	// post changes to playlist name or create new playlist
});

Route::get('/playlist-items/{pid}', 
	array
	(
		'before' => 'auth',
		function($pid)
		{
			$uid = Auth::id();

			$songs = PlaylistItem::with('song')->where('playlist_id', '=', $pid)->orderBy('order')->get();

			return Response::json($songs);
		}
	)
);

Route::post('/new-playlist-item', function()
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
});

Route::post('upload',
	array
	(
		'before'=>'auth',
		function()
		{
			$allowExt = array('mp3','ogg','m4a');
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
	)
);

App::missing(function($exception)
{
	// return 404 error page
});

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