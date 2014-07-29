@extends('_master')

@section('title')
	MOML - Log In
@stop

@section('body')
	<div id="loginform">
		{{ Form::open(array('url' => './login', 'method' => 'POST')); }}
			<table>
				<tbody>
					<tr>
						<td>{{ Form::label('email', 'Email: '); }}</td>
						<td>{{ Form::text('email'); }}</td>
					</tr>
					<tr>
						<td>{{ Form::label('password', 'Password: '); }}</td>
						<td>{{ Form::password('password'); }}</td>
					</tr>
					<tr>
						<td>{{ Form::label('remember', 'Remember Me: '); }}</td>
						<td>{{ Form::checkbox('remember'); }}</td>
					</tr>
					<tr>
						<td colspan="2">{{ Form::submit('Go!'); }}</td>
					</tr>
				</tbody>
			</table>
		{{ Form::close(); }}
	</div>
@stop