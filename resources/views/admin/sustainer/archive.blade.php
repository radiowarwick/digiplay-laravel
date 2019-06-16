@extends('layouts.app')

@section('title', 'Sustainer Archive')

@section('breadcrumbs')
	{{ Breadcrumbs::render('admin-sustainer-archive') }}
@endsection

@section('content')
    <h1>Sustainer Archive</h1>

    <p>
        Past scheduled prerecords before the current hour. Displaying 25 per page.
    </p>

    <table class="table table-responsive">
        <thead>
            <tr>
                <th class="icon"></th>
                <th>Playout Time</th>
                <th>Name</th>
                <th>Scheduler</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prerecords as $prerecord)
                <tr>
                    <td class="icon">
                        <a href="{{ route('audio-view', $prerecord->audio_id) }}">
                            <i class="fa fa-info-circle"></i>
                        </a>
                    </td>
                    <td>
						{{ \Carbon\Carbon::createFromTimestamp($prerecord->scheduled_time)->format('d-m-Y H:i') }}
                    </td>
					<td>
                        {{ $prerecord->audio->title }}
                    </td>
                    <td>
                        {{ $prerecord->user->name }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $prerecords->links() }}
@endsection