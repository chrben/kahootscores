@extends('drive.template')

@section('content')
    <div class="row">
        <div class="col-xs-12 text-center">
            <h1>Leaderboard</h1>
            @foreach ($leaderboard as $place => $data)
            <div class="lb_entry lb_place_{{ $place+1 }}">
                @if ($season !== null)
                    <span class="lb_number">{{ $place+1 }}.&nbsp;</span><a href="{{ action('LeaderboardController@viewPlayer', ['contestant' => $data['id'], 'season' => $season]) }}">{{ $data['name'] }} - {{ $data['score'] ?? 0 }}</a>
                @else
                    <span class="lb_number">{{ $place+1 }}.&nbsp;</span><a href="{{ action('LeaderboardController@viewPlayerAllSeasons', $data['id']) }}">{{ $data['name'] }} - {{ $data['score'] ?? 0 }}</a>
                @endif
            </div>
            @endforeach
            <div class="lb_upload_link"><a href="{{ action('ExcelController@showUploadForm') }}">^ upload scores ^</a></div>
        </div>
    </div>
@endsection