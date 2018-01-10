@extends('drive.template')

@section('content')
    <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2">
            <div class="well-lg">
                <form class="form-horizontal" method="post" action="{{ action('ExcelController@storeSheet') }}">
                    <h3>Edit Kahoot Results - {{ $quiz->name }} - {{ $quiz->date->formatLocalized('%d %B %Y') }}</h3>
                    {!! csrf_field() !!}
                    <div class="form-group">
                        @if ($errors->has('quizname'))
                            <div class="input-group has-error">
                                @else
                                    <div class="input-group">
                                        @endif
                            <span class="input-group-addon fixed-width-addon">Quiz name</span>
                            <input class="form-control" type="text" name="quizname" value="{{ old('quizname') != '' ? old('quizname') : $quiz->name }}"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">Forfatter {{ $quiz->creator_name }} A.K.A.</span>
                            <input class="form-control ac-names" type="text" name="creator_realname" value="{{ old('creator_realname') }}"/>
                        </div>
                    </div>
                    <input type="hidden" name="question_count" value="{{ $questionCount }}" />
                    <input type="hidden" name="quizdate" value="{{ $quiz->date }}" />
                    <input type="hidden" name="creator_name" value="{{ $quiz->creator_name }}" />
                    <table class="table">
                        <tr>
                            <th>name</th>
                            <th>correct</th>
                            <th>score</th>
                            <th></th>
                        </tr>
                        @foreach ($results as $name => $result)
                            <tr>
                                <td>{{ $name }}</td>
                                <td>{{ $result['correct'] }}/{{ $questionCount }}</td>
                                <td>{{ $result['points'] }}</td>
                                <td>
                                    <div class="form-group">
                                        @if ($errors->has('players.'.$name.'.name'))
                                            <div class="input-group has-error">
                                        @else
                                            <div class="input-group">
                                        @endif
                                            <span class="input-group-addon">A.K.A.</span>
                                            <input class="form-control ac-names" type="text" name="players[{{ $name }}][name]" value="{{ old('players.'.$name.'.name') }}"/>
                                        </div>
                                    </div>
                                </td>
                                <input type="hidden" name="players[{{ $name }}][score]" value="{{ $result['points'] }}" />
                                <input type="hidden" name="players[{{ $name }}][score_nostreak]" value="{{ $result['points_nostreak'] }}" />
                                <input type="hidden" name="players[{{ $name }}][total_time]" value="{{ $result['total_time'] }}" />
                                <input type="hidden" name="players[{{ $name }}][correct]" value="{{ $result['correct'] }}" />
                                <input type="hidden" name="players[{{ $name }}][best_streak]" value="{{ $result['best_streak'] }}" />
                            </tr>
                        @endforeach
                    </table>

                    <button class="btn btn-default" type="submit">Confirm</button>
                </form>
            </div>

            @if (count($errors) > 0)
                <div class="alert alert-dismissible alert-danger">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Oh snap!</strong> The following errors occurred:<br/>
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br/>
                    @endforeach
                </div>
            @endif
            <div class="alert alert-dismissible alert-info">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Heads up!</strong> All name fields use Autocomplete, please see if the name is present before entering something new to avoid duplicate player entries!
            </div>
        </div>
    </div>
    <script>
        $(function() {
            var playerNames = {!! json_encode($playerlist) !!};
            $(".ac-names").autocomplete({
                source: playerNames,
                minLength: 0
            }).focus(function() {
                $(this).trigger('keydown');
            });
        });
    </script>
@endsection