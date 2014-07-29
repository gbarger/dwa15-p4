var listType = 0;

$(document).ready(function()
{
	$('#libLink').click(function()
	{
		listType = 0;
		refreshLibrary();
	});

	$('#addLink').click(function()
	{
		listType = -1;
		$('#content').hide();
		$('#dropArea').show();
	});

	$('#dropArea').hide();
	$('#playlistForm').hide();

	$('#createPlaylist').click(function()
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

function makePlaylistsClickable()
{
	$('.playlists li').each(function(i, value)
	{
		var pid = value.id.substr(3);

		$(this).click(function()
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
		}
	});

	draggable();
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
}