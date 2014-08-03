var listType = 0;
var playlistItems = new Array();
var jplayerLoaded = false;
var myPlayer = null;

$(document).ready(function()
{
	jPlayerLoaded = false;

	$('#libLink').on('click',function()
	{
		listType = 0;
		refreshLibrary();
	});

	$('#addLink').on('click',function()
	{
		listType = -1;
		$('#content').hide();
		$('#dropArea').show();
	});

	$('#dropArea').hide();
	$('#playlistForm').hide();

	$('#createPlaylist').on('click',function()
	{
		$('#playlistForm').slideDown();
	});

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

	redrawScreen();
});

function draggable()
{
	$('.songRow').each(function()
	{
		$(this).draggable({revert: true});
	});
}

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
			},
			tolerance: 'touch'
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

function clickable()
{
	$('.playRow').on('click', function() 
	{
		var songPath = $(this).attr('id');

		var thisSong = [{mp3:songPath}];
		buildPlayer(thisSong);
	});
}

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
		}
	});
}

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
					'<td class="songRow" id="sid' + data[i].id + '" >' + data[i].title + '</td>' + 
					'<td>' + data[i].artist + '</td>' + 
					'<td>' + data[i].album + '</td>' + 
					'<td>' + data[i].year + '</td>' + 
					'<td>' + data[i].track + '</td>' + 
					'<td>' + data[i].genre + '</td>' + 
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

// work on this - redraw the playlists and songs
function redrawScreen()
{
	if (listType == 0)
		refreshLibrary();
	else if (listType != -1)
		refreshPlaylist(listType);

	makePlaylistsClickable();

	draggable();
	droppable();
}

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