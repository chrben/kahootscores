@extends('drive.template')

@section('content')
    <h3>Edit Kahoot Results - {{ $quizname }}</h3>
    <form method="post" action="{{ action('ExcelController@storeSheet') }}">
        {!! csrf_field() !!}
        <h4>Quiz name <input type="text" name="quizname"/></h4>
        <h5>Quiz author <input type="text" name="author"/></h5>
        <input type="hidden" name="question_count" value="{{ $questionCount }}" />
        <table>
            <tr>
                <th>name</th>
                <th>correct</th>
                <th>score</th>
            </tr>
        @foreach ($results as $name => $result)
            <tr>
                <td>{{ $name }}</td>
                <td>{{ $result['correct'] }}/{{ $questionCount }}</td>
                <td>{{ $result['points'] }}</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td> A.K.A. <input type="text" name="players[{{ $name }}][name]"/></td>
            </tr>
                <input type="hidden" name="players[{{ $name }}][score]" value="{{ $result['points'] }}" />
                <input type="hidden" name="players[{{ $name }}][score_nostreak]" value="{{ $result['points_nostreak'] }}" />
                <input type="hidden" name="players[{{ $name }}][total_time]" value="{{ $result['total_time'] }}" />
                <input type="hidden" name="players[{{ $name }}][correct]" value="{{ $result['correct'] }}" />
        @endforeach
        </table>

        <button type="submit">Confirm</button>
    </form>
    @foreach ($errors as $error)
        <div>$error</div>
    @endforeach
@endsection