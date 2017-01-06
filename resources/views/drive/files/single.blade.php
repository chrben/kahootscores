@extends('drive.template')

@section('content')
    {{ count($rows) }}
    <table>
        @foreach($rows as $row)
            <tr>
                @foreach($row as $col)
                    <td>{{ $col }}</td>
                @endforeach
            </tr>
        @endforeach
    </table>
    <a href="{{ action('DriveController@listDriveContents') }}"><- back</a>
@endsection