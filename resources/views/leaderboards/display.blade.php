@extends('drive.template')

@section('content')
    <div class="row">
        <div class="col-xs-12 text-center">
            <div class="row leaderboard-header">
                <div class="col-xs-3 text-left">
                    @if (isset($season) && \App\Season::find($season->id-1) !== null)
                        <h3><a href="{{ action('LeaderboardController@displayLeaderboard', ($season->id - 1)) }}">< Prev</a></h3>
                    @endif
                </div>
                <div class="col-xs-6 text-center">
                    @if (isset($season) && $season !== null)
                        <h1>Season {{ $season->id }}</h1>
                    @else
                        <h1>All Seasons</h1>
                    @endif
                </div>
                <div class="col-xs-3 text-right">
                    @if (isset($season) && \App\Season::find($season->id+1) !== null)
                        <h3><a href="{{ action('LeaderboardController@displayLeaderboard', ($season->id + 1)) }}">Next ></a></h3>
                    @endif
                </div>
            </div>
            @foreach ($leaderboard as $place => $data)
            <div class="lb_entry lb_place_{{ $place+1 }}">
                @if ($season !== null)
                    <span class="lb_number">{{ $place+1 }}.&nbsp;</span><a href="{{ action('LeaderboardController@viewPlayer', ['contestant' => $data['id'], 'season' => $season]) }}">{{ $data['name'] }} - {{ $data['score'] ?? 0 }}</a>
                @else
                    <span class="lb_number">{{ $place+1 }}.&nbsp;</span><a href="{{ action('LeaderboardController@viewPlayerAllSeasons', $data['id']) }}">{{ $data['name'] }} - {{ $data['score'] ?? 0 }}</a>
                @endif
            </div>
            @endforeach
            <div class="lb_upload_link">
                <a href="{{ action('ExcelController@showUploadForm') }}">upload results</a>
                @if (isset($season) && $season !== null)
                    <br/><a href="{{ action('LeaderboardController@displayAllSeasonsBoard', $season) }}">show combined scores</a>
                @else
                    <br/><a href="{{ action('LeaderboardController@redirectToCurrentSeason') }}">show current season</a>
                @endif
            </div>

        </div>
    </div>
@endsection