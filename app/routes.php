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

Route::get('/login', function() 
{
	// display login page
});

Route::post('/login', function() 
{
	// post login details and confirm auth
});

Route::get('/signup', function()
{
	// display signup page
});

Route::post('/signup', function()
{
	// post to signup page, then log in
});

Route::get('/library/{format?}', function($format = 'html')
{
	// display the library page, or return songs in json format
});

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