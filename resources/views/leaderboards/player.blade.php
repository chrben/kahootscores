@extends('drive.template')

@section('content')
    <div class="row player-container">
        <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-lg-8 col-lg-offset-2">
            <div class="row">
                <div class="col-xs-12">
                    <h2>{{ $contestant->name }}</h2>
                    <span class="pv_aliases">Also known as: {{ implode(', ', $aliases) }}</span>
                </div>
            </div>
            @if (!isset($dataMissing) || $dataMissing == false)
            <div class="row">
                <div class="col-sm-4">
                    <div class="well">
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <span class="pv_score_header">Total Score</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <span class="pv_score_current pv_bold">{{ round($score['yours']['score']) }}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <span class="pv_score_header">Ranked <span class="pv_bold">{{ $score['yours']['placement'] }}</span> out of <span class="pv_bold">{{ $score['count'] }}</span></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 text-center">
                                <span class="pv_score_global">Best: {{ $score['best']['name'] }}<br/>{{ round($score['best']['score']) }}</span>
                            </div>
                            <div class="col-sm-6 text-center">
                                <span class="pv_score_global">Worst: {{ $score['worst']['name'] }}<br/>{{ round($score['worst']['score']) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="well">
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <span class="pv_aspa_header">Avg Score Per Answer</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <span class="pv_aspa_current pv_bold">{{ round($aspa['yours']['ASPA']) }}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <span class="pv_aspa_header">Ranked <span class="pv_bold">{{ $aspa['yours']['placement'] }}</span> out of <span class="pv_bold">{{ $aspa['count'] }}</span></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 text-center">
                                <span class="pv_aspa_global">Best: {{ $aspa['best']['name'] }}<br/>{{ round($aspa['best']['ASPA']) }}</span>
                            </div>
                            <div class="col-sm-6 text-center">
                                <span class="pv_aspa_global">Worst: {{ $aspa['worst']['name'] }}<br/>{{ round($aspa['worst']['ASPA']) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="well">
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <span class="pv_streak_header">Highest Answer Streak</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <span class="pv_streak_best pv_bold">{{ $streaks['yours']['streak'] }}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <span class="pv_streak_header">Ranked <span class="pv_bold">{{ $streaks['yours']['placement'] }}</span> out of <span class="pv_bold">{{ $streaks['count'] }}</span></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 text-center">
                                <span class="pv_streak_global">Best: {{ $streaks['best']['name'] }}<br/>{{ $streaks['best']['streak'] }}</span>
                            </div>
                            <div class="col-sm-6 text-center">
                                <span class="pv_streak_global">Worst: {{ $streaks['worst']['name'] }}<br/>{{ $streaks['worst']['streak'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                </div>
                <div class="col-sm-4">
                    <div class="well">
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <span class="pv_accur_header">Answer Accuracy</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <span class="pv_accur_current pv_bold">{{ round($accuracy['yours']['accuracy']*100,1) }}%</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <span class="pv_accur_header">Ranked <span class="pv_bold">{{ $accuracy['yours']['placement'] }}</span> out of <span class="pv_bold">{{ $accuracy['count'] }}</span></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 text-center">
                                <span class="pv_accur_global">Best: {{ $accuracy['best']['name'] }}<br/>{{ round($accuracy['best']['accuracy']*100,1) }}%</span>
                            </div>
                            <div class="col-sm-6 text-center">
                                <span class="pv_accur_global">Worst: {{ $accuracy['worst']['name'] }}<br/>{{ round($accuracy['worst']['accuracy']*100,1) }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                </div>
            </div>
            @else
                <h4>Data is missing, unable to display stats for this season.</h4>
            @endif
            <div class="row">
                <div class="col-xs-12 text-center">
                    @if ($season !== null)
                        <a href="{{ action('LeaderboardController@displayLeaderboard', $season) }}"> < back to leaderboard</a>
                    @else
                        <a href="{{ action('LeaderboardController@displayAllSeasonsBoard') }}"> < back to leaderboard</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection