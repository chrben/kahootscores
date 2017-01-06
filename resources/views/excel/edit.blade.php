@extends('drive.template')

@section('content')
    <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2">
            <div class="well-lg">
                <form class="form-horizontal" method="post" action="{{ action('ExcelController@storeSheet') }}">
                    <h3>Edit Kahoot Results - {{ $quizname }}</h3>
                    {!! csrf_field() !!}
                    <div class="form-group">
                        @if ($errors->has('quizname'))
                            <div class="input-group has-error">
                                @else
                                    <div class="input-group">
                                        @endif
                            <span class="input-group-addon">Quiz name</span>
                            <input class="form-control" type="text" name="quizname" value="{{ old('quizname') }}"/>
                        </div>
                    </div>
                    <div class="form-group">
                        @if ($errors->has('author'))
                            <div class="input-group has-error">
                        @else
                            <div class="input-group">
                        @endif
                            <span class="input-group-addon">Quiz author</span>
                            <input class="form-control ac-names" type="text" name="author" value="{{ old('author') }}"/>
                        </div>
                    </div>
                    <input type="hidden" name="question_count" value="{{ $questionCount }}" />
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
        </div>
    </div>
    <script>
        $(function() {
            var playerNames = {!! json_encode($playerlist) !!};
            $(".ac-names").autocomplete({
                source: playerNames
            });
        });
    </script>
@endsection