<!DOCTYPE html>
<html lang="en">
<head>
	<title>
		@yield('title','MOML')
	</title>
	<meta charset="utf-8" />

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js"></script>
	<link rel="stylesheet" type="text/css" href="./css/dropzone.css" />
	<link rel="stylesheet" type="text/css" href="./css/player-skins/blue.monday/jplayer.blue.monday.css" />
	<link rel="stylesheet" type="text/css" href="./css/styles.css" />
	<link rel="stylesheet" type="text/css" href="./css/library.css" />
	<link rel="icon" type="image/ico" href="/favicon.ico" />
</head>
<body>

	@if (isset($errors) && is_array($errors) && count($errors) > 0)
		<div id="errorText">
			<ul>
				@foreach ($errors as $e)
					<li>{{$e}}</li>
				@endforeach
			</ul>
		</div>
	@endif

	@yield('body')
</body>
</html>