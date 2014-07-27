<!DOCTYPE html>
<html lang="en">
<head>
	<title>
		@yield('title','MOML')
	</title>
	<meta charset="utf-8" />

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="./css/styles.css" />
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