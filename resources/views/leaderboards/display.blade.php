@extends('drive.template')

@section('content')
    <h1>Leaderboards</h1>
    @foreach ($leaderboard as $place => $data)
    <div class="lb_entry lb_place_{{ $place+1 }}">
        <a href="{{ action('LeaderboardController@viewPlayer', $data['id']) }}"><span class="lb_number">{{ $place+1 }}.</span><span>{{ $data['name'] }} - {{ $data['score'] ?? 0 }}</span></a>
    </div>
    @endforeach
    <div class="lb_upload_link"><a href="{{ action('ExcelController@showUploadForm') }}">^ upload scores ^</a></div>
@endsection