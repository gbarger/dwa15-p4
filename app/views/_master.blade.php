<!DOCTYPE html>
<html lang="en">
<head>
	<title>
		@yield('title','MOML')
	</title>
	<meta charset="utf-8" />
</head>
<body>
	<h1>My Online Music Library</h1>

	<div id="controls">
	</div>

	<div id="nav">
		<ul>
			<li><a href="./library">My Library</a></li>
		</ul>
		<ul id="playlists">
			@foreach($playlists as $playlist)
				<li>$playlist->name</li>
			@endforeach
		</ul>
	</div>

	<div id="content">
		@yield('content')
	</div>
</body>
</html>