@extends('layouts.app')

@section('title', 'Audio Library')

@section('content')
	<h1>Audio Library</h1>

	@include('forms.audio-search')

	<div class='row'>
		<div class='col-md-6'>
			<h3>Upload Audio</h3>
			<p>
				Have some music that you want added to Digiplay? Send an email to <a href='mailto:music@radio.warwick.ac.uk'>music@radio.warwick.ac.uk</a> with your music attached at least 48 hours before your show. Make sure the music is high quality! Be sure to include the following in the email:

				<ul>
					<li>Track Name</li>
					<li>Artist Name</li>
					<li>Album Name</li>
					<li>Does the track contain explicit content?</li>
				</ul>
			</p>
		</div>
		<div class='col-md-6'>
			<h3>Latest Uploads</h3>
			<table class="table table-striped table-sm">
				<thead>
					<tr>
						<th>Title</th>
						<th>Artist</th>
						<th>Origin</th>
					</tr>
				</thead>
				<tbody>
					@foreach($latest as $l)
						<tr>
							<td>{{ $l->title }}</td>
							<td>{{ $l->artist->first()->name }}</td>
							<td>{{ $l->origin }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection