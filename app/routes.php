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

Route::get('/library/{format?}', function($format = 'html')
{
	$testQuery = User::first();

	$user = new User();
	$user->username = 'test@test.com';
	$user->password = 'test';
	$remember_token = 'yep';
	$user->save();
	$songs = array();

	for ($i = 1; $i <= 10; $i++)
	{
		$song = new Song();
		$song->artist = 'Artist ' . $i;
		$song->user_id = $user->id;
		$song->save();
	}

	$getSongs = Song::all();

	$plist = new Playlist();
	$plist->name = 'test';
	$plist->save();

	return Response::json($getSongs);

});

Route::get('/login', 'UserController@getLogin');