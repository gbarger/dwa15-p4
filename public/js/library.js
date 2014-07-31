var listType = 0;

$(document).ready(function()
{
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
					data: {sid: dropId, pid: playlistId, type: dropType},
					success: function(data)
					{
						refreshLibrary();
					}
				});
			}
		})
	});

	$('#trash').droppable({
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
		}
	});
}

function clickable()
{
	$('.playRow').on('click', function() 
	{
		var songpath = $(this).attr('id');
		var songs = [
			{title:'test',mp3:songpath},
			{title:'test2',mp3:songpath},
			{title:'test3',mp3:songpath}
		];

		buildPlayer(songs);
	});
}

function makePlaylistsClickable()
{
	$('.playlists li').each(function(i, value)
	{
		var pid = value.id.substr(3);

		$(this).on('click',function()
		{
			refreshPlaylist(pid);
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

			for (var i = 0; i < data.length; i++)
			{
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

			$('#dropArea').hide();
			$('#content').show();

			draggable();
			clickable();
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

			for (var i = 0; i < data.length; i++)
			{
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

			$('#dropArea').hide();
			$('#content').show();

			draggable();
			clickable();
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

	refreshPlaylistMenu
	makePlaylistsClickable();

	draggable();
	droppable();

	buildPlayer();
}

function buildPlayer(songList)
{
	if (songList != null && songList.length > 0)
	{
		var songAlert = '';
		for (var i = 0; i < songList.length; i++)
			songAlert += songList[i].title + ': ' + songList[i].mp3 + ' ';

		alert(songList[0].mp3);
	}

	new jPlayerPlaylist({
		jPlayer: "#jquery_jplayer_1",
		cssSelectorAncestor: "#jp_container_1"
	},
	songList
	, {
		swfPath: "js",
		supplied: "mp3",
		wmode: "window",
		smoothPlayBar: true,
		keyEnabled: true
	});
}