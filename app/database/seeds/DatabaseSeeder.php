<?php

class DatabaseSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('UserTableSeeder');
		$this->command->info('User table seeded.');

		$this->call('SongTableSeeder');
		$this->command->info('Song table seeded.');

		$this->call('PlaylistTableSeeder');
		$this->command->info('Playlist table seeded.');

		$this->call('PlaylistItemTableSeeder');
		$this->command->info('Playlist Item table seeded.');
	}
}

class UserTableSeeder extends Seeder
{
	public function run()
	{
		$user = new User;
		$user->email = 'test@test.com';
		$user->password = '$2y$10$nk0nAEtN1ZagcOvjiw47GuleDrmiNfBXyOWJXo0l5D2Lm1fTIiz2C';
		$user->save();
	}
}

class SongTableSeeder extends Seeder
{
	public function run()
	{
		$song = new Song;
		$song->user_id = 1;
		$song->image_path = '';
		$song->file_path = './songs/1/01_-_Brad_Sucks_-_In_Your_Face.mp3';
		$song->title = 'In Your Face';
		$song->artist = 'Brad Sucks';
		$song->year = 2014;
		$song->track = 1;
		$song->genre = 'Opera';
		$song->save();

		$song2 = new Song;
		$song2->user_id = 1;
		$song2->image_path = '';
		$song2->file_path = './songs/1/02_-_Brad_Sucks_-_Come_Back.mp3';
		$song2->title = 'Come Back';
		$song2->artist = 'Brad Sucks';
		$song2->year = 2014;
		$song2->track = 1;
		$song2->genre = 'Opera';
		$song2->save();

		$song3 = new Song;
		$song3->user_id = 1;
		$song3->image_path = '';
		$song3->file_path = './songs/1/03_-_Brad_Sucks_-_Feel_Free_Plastic_Surgery.mp3';
		$song3->title = 'Recital - German';
		$song3->artist = 'Brad Sucks';
		$song3->year = 2014;
		$song3->track = 1;
		$song3->genre = 'Opera';
		$song3->save();

		$song3 = new Song;
		$song3->user_id = 1;
		$song3->image_path = '';
		$song3->file_path = './songs/1/04_-_Brad_Sucks_-_Guess_Whos_a_Mess.mp3';
		$song3->title = 'Recital - Italian';
		$song3->artist = 'Brad Sucks';
		$song3->year = 2014;
		$song3->track = 1;
		$song3->genre = 'Opera';
		$song3->save();
	}
}

class PlaylistTableSeeder extends Seeder
{
	public function run()
	{
		$plist = new Playlist;
		$plist->user_id = 1;
		$plist->name = 'Test Playlist 1';
		$plist->save();

		$plist2 = new Playlist;
		$plist2->user_id = 1;
		$plist2->name = 'My Second Playlist Test';
		$plist2->save();
	}
}

class PlaylistItemTableSeeder extends Seeder
{
	public function run()
	{
		$plistItem = new PlaylistItem;
		$plistItem->order = 1;
		$plistItem->playlist_id = 1;
		$plistItem->song_id = 1;
		$plistItem->save();

		$plistItem2 = new PlaylistItem;
		$plistItem2->order = 2;
		$plistItem2->playlist_id = 1;
		$plistItem2->song_id = 2;
		$plistItem2->save();

		$plistItem3 = new PlaylistItem;
		$plistItem3->order = 1;
		$plistItem3->playlist_id = 2;
		$plistItem3->song_id = 3;
		$plistItem3->save();

		$plistItem4 = new PlaylistItem;
		$plistItem4->order = 2;
		$plistItem4->playlist_id = 2;
		$plistItem4->song_id = 4;
		$plistItem4->save();
	}
}
