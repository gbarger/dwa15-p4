<!DOCTYPE html>
<html lang="en">
<head>
	<title>
		@yield('title','MOML - Login')
	</title>
	<meta charset="utf-8" />
</head>
<body>
	<h1>My Online Music Library</h1>

	<div id="loginform">
		<form action="./login" method="POST">
			<table>
				<tbody>
						<tr>
							<td><label for="un">Username: </label></td>
							<td><input type="text" name="username" id="un" /></td>
						</tr>
						<tr>
							<td><label for="pw">Password: </label></td>
							<td><input type="password" name="password" id="pw" /></td>
						</tr>
						<tr>
							<td><label for="remember">Remember Me: </label></td>
							<td><input type="checkbox" name="remember" id="remember" /></td>
						</tr>
						<tr>
							<td colspan="2"><input type="submit" name="submit" value="submit" id="submit" /></td>
						</tr>
				</tbody>
			</table>
		</form>
	</div>
</body>
</html>