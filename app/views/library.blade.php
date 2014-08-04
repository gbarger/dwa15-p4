@extends('_master')

@section('title')
	MOML - Library
@stop

@section('body')
	<script src="./js/jquery.tablesorter.min.js"></script>
	<script src="./js/library.js"></script>
	<script src="./js/dropzone.js"></script>
	<script src="./js/jquery.jplayer.min.js"></script>
	<script src="./js/jplayer.playlist.min.js"></script>

	<div id="controls" class="gradient">
		<div id="userActions">
			<a href="./logout">Sign Out</a> | 
			<a href="./update-profile">Update Profile</a> | 
			<a href="./" id="helpLink">Help</a>
		</div>

		<div id="searchDiv">
			<form id="searchForm">
				<input type="text" name="search" id="searchBox" />
			</form>
		</div>

		<div id="jquery_jplayer_1" class="jp-jplayer"></div>

		<div id="jp_container_1" class="jp-audio">
			<div class="jp-type-playlist">
				<div class="jp-gui jp-interface">
					<ul class="jp-controls">
						<li><a href="javascript:;" class="jp-previous" tabindex="1">previous</a></li>
						<li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
						<li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
						<li><a href="javascript:;" class="jp-next" tabindex="1">next</a></li>
						<li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
						<li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>
						<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>
						<li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>
					</ul>
					<div class="jp-progress">
						<div class="jp-seek-bar">
							<div class="jp-play-bar"></div>
						</div>
					</div>
					<div class="jp-volume-bar">
						<div class="jp-volume-bar-value"></div>
					</div>
					<div class="jp-time-holder">
						<div class="jp-current-time"></div>
						<div class="jp-duration"></div>
					</div>
					<ul class="jp-toggles">
						<li><a href="javascript:;" class="jp-shuffle" tabindex="1" title="shuffle">shuffle</a></li>
						<li><a href="javascript:;" class="jp-shuffle-off" tabindex="1" title="shuffle off">shuffle off</a></li>
						<li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat">repeat</a></li>
						<li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off">repeat off</a></li>
					</ul>
				</div>

				<div class="jp-no-solution">
					<span>Update Required</span>
					To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
				</div>
			</div>
		</div>
	</div>

	<div id="nav">
		<h5>LIBRARY</h5>
		<ul>
			<li id="libLink"><img src="./images/music.png" />Music</li>
			<li id="addLink"><img src="./images/add.png" />Upload</li>
		</ul>

		<h5>PLAYLISTS</h5>
		<ul id="playlists" class="playlists">
		</ul>

		<form id="playlistForm">
			<input type="text" name="plistName" id="plistName" /><br />
			<input type="submit" name="submit" value="Create!" />
		</form>
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
					<th></th>
					<th>Title</th>
					<th>Artist</th>
					<th>Album</th>
					<th>Year</th>
					<th>Track</th>
					<th>Genre</th>
				</tr>
			</thead>
			<tbody id="songList">
			</tbody>
		</table>
	</div>

	<div id="footer" class="gradient">
		<div id="createPlaylist"><img src="./images/add.png" /></div>
		<div id="trash"><img src="./images/trash.png" /></div>
	</div>

	<div id="helpDiv">
		<img src="./images/help.png" />
	</div>
@stop