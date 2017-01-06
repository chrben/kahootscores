@extends('drive.template')

@section('content')
    <div class="content">
    <h2>{{ $contestant->name }}</h2>
    <div><span>Total score: {{ $contestant->results->sum('score') ?? 0}}</span></div>
    <div><span>Total score without streak bonus: {{ $contestant->results->sum('score_nostreak') ?? 0 }}</span></div>
    <div><span>Total correct questions: {{ $correct_sum }}</span></div>
    <div><span>Accuracy: {{ round($correct_pct * 100.0, 1) }}%</span></div>
    <div><span>Average Score Per Answer: {{ ($question_sum > 0 ? round($contestant->results->sum('score')/$question_sum) : 0) }}</span></div>
    <br>
    <div><a href="{{ action('LeaderboardController@displayLeaderboard') }}"> < back to leaderboard</a></div>
    </div>
@endsection