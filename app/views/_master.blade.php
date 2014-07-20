<!DOCTYPE html>
<html lang="en">
<head>
	<title>
		@yield('title','DBF')
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
			@yield('playlists')
		</ul>
	</div>

	<div id="content">
		@yield('content')
	</div>
</body>
</html>