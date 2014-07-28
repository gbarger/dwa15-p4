$(document).ready(function()
{
	$('#libLink').click(function()
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
	});

	$('#addLink').click(function()
	{
		$('#content').hide();
		$('#dropArea').show();
	});

	$('.playlists li').each(function(i, value)
	{
		var pid = value.id.substr(3);

		$(this).click(function()
		{
			$.ajax(
			{
				url: './playlist-items/' + pid,
				type: 'GET',
				success: function(data)
				{
					var tableData = '';

					for (var i = 0; i < data.length; i++)
					{
						tableData += '<tr>' + 
							'<td>' + data[i].song.title + '</td>' + 
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
		});
	});

	$('#dropArea').hide();

	$('.songRow').each(function()
	{
		$(this).draggable();
	});

	$('#playlists li').each(function()
	{
		$(this).droppable
		({
			drop: function(event, ui)
			{
				var songId = ui.draggable.attr('id').substr(3);
				var playlistId = this.id.substr(3);

				$.ajax(
				{
					url: './new-playlist-item',
					type: 'POST',
					data: {sid: songId, pid: playlistId},
					success: function(data)
					{
						console.log('updated playlist');
						// refresh display table with playlist
					}
				});
			}
		})
	});
});

function draggable()
{
	$('.songRow').each(function()
	{
		$(this).draggable();
	});
}