// global variables needed
var listType = 0; // the type of list to reload 0, greater than 0 is a playlist
var playlistItems = new Array(); // the list of items for the music player
var jplayerLoaded = false; // the player only needs to be loaded once, then updated
var myPlayer = null; // the player

// when the DOM is ready
$(document).ready(function()
{
	jPlayerLoaded = false;

	// display the library when clicking on it
	$('#libLink').on('click',function()
	{
		listType = 0;
		refreshLibrary();
	});

	// hide the music table and show the upload box when clicking
	$('#addLink').on('click',function()
	{
		listType = -1;
		$('#content').hide();
		$('#dropArea').show();
	});

	// hide the upload and the form for adding new playlists by default
	$('#dropArea').hide();
	$('#playlistForm').hide();

	// show the new playlist form when clicking
	$('#createPlaylist').on('click',function()
	{
		$('#playlistForm').slideDown();
	});

	// when submitting the playlist form create the playlist and add show it in the nav
	$('#playlistForm').submit(function(event)
	{
		var nameVal = $('#plistName').val();

		$.ajax(
		{
			url: './new-playlist',
			type: 'POST',
			data: {plistName: nameVal},
			success: function(data)
			{
				var plistId = data;

				$('#playlistForm').slideUp();
				$('#plistName').val('');
				
				$('#playlists').append(
					'<li id="pid' + plistId + '" class="songRow" style="display: none;" >' + nameVal + '</li>'
				);

				$('#pid' + plistId).slideDown();

				redrawScreen();
			}
		});

		redrawScreen();
		event.preventDefault();
	});

	// as typing happens in the search box, filter the song table
	$('#searchBox').on('keyup', function()
	{
		var searchVal = $(this).val().toLowerCase();
		
		$('#songList tr').each(function()
		{
			var rowText = $(this).text().toLowerCase();

			if (rowText.indexOf(searchVal) == -1)
				$(this).hide();
			else
				$(this).show();
		});
	});

	// hide the help on load
	$('#helpDiv').hide();

	// display the help when clicking the help link
	$('#helpLink').on('click', function()
	{
		$('#helpDiv').show();

		event.preventDefault();
	});

	// hide the help again when clicking anywhere on the div
	$('#helpDiv').on('click', function()
	{
		$('#helpDiv').hide();
	});

	// init all the screen items
	redrawScreen();
});

// make the correct items draggable
function draggable()
{
	$('.songRow').each(function()
	{
		$(this).draggable({revert: true});
	});
}

// make the playlists and trash droppable
function droppable()
{
	$('#playlists li').each(function()
	{
		$(this).droppable
		({
			drop: function(event, ui)
			{
				var dropType = ui.draggable.attr('id').substr(0,3);
				var dropId = ui.draggable.attr('id').substr(3);
				var playlistId = this.id.substr(3);

				$.ajax(
				{
					url: './new-playlist-item',
					type: 'POST',
					data: {dropped: dropId, pid: playlistId, type: dropType},
					success: function(data)
					{
						refreshLibrary();
						refreshPlaylistMenu();
						makePlaylistsClickable();
					}
				});
			}
		})
	});

	$('#trash').droppable(
	{
		drop: function(event, ui)
		{
			var draggedItem = ui.draggable.attr('id');
			var draggedId = draggedItem.substr(3);
			var deleteType = '';

			if (draggedItem.indexOf('sid') != -1)
			{
				deleteType = 'song';
			}
			else if (draggedItem.indexOf('pid') != -1)
			{
				deleteType = 'playlist';
			}
			else if (draggedItem.indexOf('iid') != -1)
			{
				deleteType = 'playlistItem';
			}

			$.ajax(
			{
				url: './delete',
				type: 'POST',
				data: {type: deleteType, id: draggedId},
				success: function(data)
				{
					refreshLibrary();
				}
			});

			if (deleteType == 'playlist' && listType == draggedId)
				listType = 0;

			$('#' + draggedItem).remove();

			redrawScreen();
		},
		tolerance: 'touch'
	});
}

// make the songs clickable to send the correct song to the player
function clickable()
{
	$('.playRow').on('click', function() 
	{
		var songPath = $(this).attr('id');

		var thisSong = [{mp3:songPath}];
		buildPlayer(thisSong);
	});
}

// make the playlists clickable so they will load and play the playlist
function makePlaylistsClickable()
{
	$('.playlists li').each(function(i, value)
	{
		var pid = value.id.substr(3);

		// display the playlist in the table
		$(this).on('click',function()
		{
			refreshPlaylist(pid);
		});

		// enable inline editing of playlist name
		$(this).on('dblclick', function()
		{
			var thisValue = $(this).html();

			var thisForm = '<form><input class="inline-edit" type="text" value="' + thisValue + 
				'" /><input type="submit" name="submit" value="save" /></form>';

			$(this).html(thisForm);
			$(this).unbind('dblclick');

			$(this).on('submit', function()
			{
				var newVal = $('.inline-edit').val();
				$(this).html(newVal);
				event.preventDefault();

				$.ajax(
				{
					url: './edit-playlist',
					type: 'POST',
					data: {pid: pid, newValue: newVal},
					success: function(data)
					{
						refreshPlayListMenu();
					}
				});
			});
		});
	});
}

// this will refresh the list of playlists in the left nav
function refreshPlaylistMenu()
{
	$.ajax(
	{
		url: './playlists',
		type: 'GET',
		success: function(data)
		{
			var liData = '';

			for (var i = 0; i < data.length; i++)
			{
				liData += '<li id="pid' + data[i].id + '" class="songRow">' + data[i].name + '</li>';
			}

			$('#playlists').html(liData);

			makePlaylistsClickable();
			droppable();
		}
	});
}

// this will refresh the listing of songs in the table for a given playlist
function refreshPlaylist(playlistId)
{
	listType = playlistId;

	$.ajax(
	{
		url: './playlist-items/' + playlistId,
		type: 'GET',
		success: function(data)
		{
			var tableData = '';
			playlistItems = new Array();

			for (var i = 0; i < data.length; i++)
			{
				if (typeof data[i].song.file_path === 'undefined')
					continue;

				playlistItems.push({mp3:data[i].song.file_path});

				tableData += '<tr>' + 
					'<td class="playRow" id="' + data[i].song.file_path + '"><img src="./images/play-icon.png" /></td>' + 
					'<td class="songRow" id="iid' + data[i].id + '">' + data[i].song.title + '</td>' + 
					'<td>' + data[i].song.artist + '</td>' + 
					'<td>' + data[i].song.album + '</td>' + 
					'<td>' + data[i].song.year + '</td>' + 
					'<td>' + data[i].song.track + '</td>' + 
					'<td>' + data[i].song.genre + '</td>' + 
					'</tr>';
			}

			$('#songList').html(tableData);
			$('#content table').tablesorter();

			$('#dropArea').hide();
			$('#content').show();

			draggable();
			clickable();
			buildPlayer(playlistItems);
		}
	});
}

// display the library songs in the songs table
function refreshLibrary()
{
	$.ajax(
	{
		url: './library/json',
		type: 'GET',
		success: function(data)
		{
			var tableData = '';
			playlistItems = new Array();

			for (var i = 0; i < data.length; i++)
			{
				playlistItems.push({mp3:data[i].file_path});

				tableData += '<tr>' + 
					'<td class="playRow" id="' + data[i].file_path + '"><img src="./images/play-icon.png" /></td>' + 
					'<td class="songRow title" id="sid' + data[i].id + '" >' + data[i].title + '</td>' + 
					'<td class="artist" >' + data[i].artist + '</td>' + 
					'<td class="album" >' + data[i].album + '</td>' + 
					'<td class="year" >' + data[i].year + '</td>' + 
					'<td class="track" >' + data[i].track + '</td>' + 
					'<td class="genre" >' + data[i].genre + '</td>' + 
					'</tr>';
			}

			$('#songList').html(tableData);
			$('#content table').tablesorter();

			$('#songList td').each(function()
			{
				$(this).on('dblclick', function()
				{
					var thisValue = $(this).html();
					var thisType = $(this).attr('class');
					var songId = $(this).parent().children(':nth-child(2)').attr('id').substr(3);

					var thisForm = '<form><input class="inline-edit" type="text" value="' + thisValue + 
						'" /><input type="submit" name="submit" value="save" /></form>';

					$(this).html(thisForm);
					$(this).unbind('dblclick');

					$(this).on('submit', function()
					{
						var newVal = $('.inline-edit').val();
						$(this).html(newVal);
						event.preventDefault();

						$.ajax(
						{
							url: './update-song',
							type: 'POST',
							data: {sid: songId, type: thisType, newValue: newVal},
							success: function(data)
							{
								refreshLibrary();
							}
						});
					});
				});
			});
			$('#dropArea').hide();
			$('#content').show();

			draggable();
			clickable();
			buildPlayer(playlistItems);
		}
	});
}

// redraw the ajax items on the screen
function redrawScreen()
{
	if (listType == 0)
		refreshLibrary();
	else if (listType != -1)
		refreshPlaylist(listType);

	refreshPlaylistMenu();

	draggable();
	droppable();
}

// either build the player if it hasn't been played yet, or set the playlist if the plalyer has been loaded
function buildPlayer(playList)
{
	if (jPlayerLoaded)
	{
		myPlayer.setPlaylist(playList);
		myPlayer.play(0);
	}
	else
	{
		myPlayer = new jPlayerPlaylist({
			jPlayer: "#jquery_jplayer_1",
			cssSelectorAncestor: "#jp_container_1"
		},
		playList
		,{
			playlistOptions: {autoPlay: true},
			swfPath: "js",
			supplied: "mp3",
			wmode: "window",
			smoothPlayBar: true,
			keyEnabled: true
		});

		jPlayerLoaded = true;
	}
}