@extends('_master')

@section('title')
	MOML - Sign Up
@stop

@section('body')
	<div id="loginform">
		{{ Form::open(array('url' => '/update-profile', 'method' => 'POST')); }}
			<table>
				<tbody>
					<tr>
						<td>{{ Form::label('email', 'Email: '); }}</td>
						<td>{{ Form::text('email', $email); }}</td>
					</tr>
					<tr>
						<td>{{ Form::label('newPassword', 'New Password: '); }}</td>
						<td>{{ Form::password('newPassword'); }}</td>
					</tr>
					<tr>
						<td>{{ Form::label('confirm', 'Re-enter New Password: '); }}</td>
						<td>{{ Form::password('confirm'); }}</td>
					</tr>
					<tr>
						<td colspan="2">{{ Form::submit('Go!'); }}</td>
					</tr>
				</tbody>
			</table>
		{{ Form::close(); }}
	</div>
@stop