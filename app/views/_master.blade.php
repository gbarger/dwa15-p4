<!DOCTYPE html>
<html lang="en">
<head>
	<title>
		@yield('title','MOML')
	</title>
	<meta charset="utf-8" />
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