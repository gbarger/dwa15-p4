@extends('_master')

@section('title')
	MOML - Library
@stop

@section('body')
	<link rel="stylesheet" type="text/css" href="./css/dropzone.css" />
	<script src="./js/library.js"></script>
	<script src="./js/dropzone.js"></script>
	<style>
		#nav
		{
			min-height: 400px;
		}
		.dropzone
		{
			position: static;
			width: 100%;
		}
	</style>

	<div id="controls" class="gradient">
		<h5>My Online Muic Library</h5>
	</div>

	<div id="nav">
		<h5>LIBRARY</h5>
		<ul>
			<li id="libLink">Music</li>
			<li id="addLink">Add Music</li>
		</ul>

		<h5>PLAYLISTS</h5>
		<ul class="playlists">
			@foreach($playlists as $plist)
				<li id="pid{{$plist->id}}">{{$plist->name}}</li>
			@endforeach
		</ul>
	</div>

	<div id="dropArea">
		<form action="./upload" class="dropzone" id="my-awesome-dropzone">
			<div class="fallback">
				<input name="file[]" type="file" multiple />
			</div>
		</form>
	</div>

	<div id="content">
		<table>
			<thead>
				<tr>
					<th>Title</th>
					<th>Artist</th>
					<th>Album</th>
				</tr>
			</thead>
			<tbody id="songList">
				@foreach($songs as $song)
					<tr>
						<td>{{$song->title}}</td>
						<td>{{$song->artist}}</td>
						<td>{{$song->album}}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	<div id="footer" class="gradient">
		<h5>footer here</h5>
	</div>
@stop