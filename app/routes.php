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

Route::get('/', array('before' => 'guest', 'uses' => 'UserController@getUserLogin'));

Route::get('/login', array('before' => 'guest', 'uses' => 'UserController@getUserLogin'));

Route::post('/login', array('before' => 'csrf', 'uses' => 'UserController@postUserLogin'));

Route::get('/logout', 'UserController@getUserLogout');

Route::get('/signup', array('before' => 'guest', 'uses' => 'UserController@getUserSignup'));

Route::post('/signup', array('before' => 'csrf', 'uses' => 'UserController@postUserSignup'));

Route::get('/update-profile', array('before' => 'auth', 'uses' => 'UserController@getUpdateProfile'));

Route::post('/update-profile', array('before' => 'csrf', 'uses' => 'UserController@postUpdateProfile'));

Route::get('/library/{format?}', array('before' => 'auth', 'uses' => 'SongController@getLibrary'));

Route::get('/playlists', 'PlaylistController@getPlaylists');

Route::post('/edit-playlist', 'PlaylistController@postEditPlaylist');

Route::post('/new-playlist', array('before' => 'auth', 'uses' => 'PlaylistController@postNewPlaylist'));

Route::get('/playlist-items/{pid}', array('before' => 'auth','uses' => 'PlaylistItemController@getPlaylistItems'));

Route::post('/new-playlist-item', 'PlaylistItemController@postNewPlaylistItem');

Route::post('/delete', array('before' => 'auth', 'uses' => 'CrossObjectController@postDelete'));

Route::post('/upload', array('before' => 'auth', 'uses' => 'SongController@postSongUpload'));

App::error(function($exception)
{
	return Response::view('error', array(), 404);
});