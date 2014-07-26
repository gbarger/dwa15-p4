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

			return View::make('library')
				->with('songs', $songs)
				->with('playlists', $plists);
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
	// post changes to playlist name using playlist id
});

Route::get('/playlist-items/{id}', function()
{
	// pass playlist id and get list of songs for playlist
});

Route::post('/playlist-item', function()
{
	// post update to playlist-item (playlist order)
});

App::missing(function($exception)
{
	// return 404 error page
});