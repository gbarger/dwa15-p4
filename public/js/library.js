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
						'<td>' + data[i].title + '</td>' + 
						'<td>' + data[i].artist + '</td>' + 
						'<td>' + data[i].album + '</td>' + 
						'</tr>';
				}

				$('#songList').html(tableData);

				$('#dropArea').hide();
				$('#content').show();
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
});