@extends('_master')

@section('body')
	<div id="controls">
		Control Header
	</div>

	<div id="nav">
		<ul>
			<li>Library</li>
			<li>Add Music</li>
		</ul>

		<ul>
			@foreach($playlists as $plist)
				<li>{{$plist->name}}</li>
			@endforeach
		</ul>
	</div>

	<div id="content">
		<ul>
			@foreach($songs as $song)
				<li>{{$song->title}}</li>
			@endforeach
		</ul>
	</div>
@stop