@extends('drive.template')

@section('content')
    <div class="content">
    {{ count($files_list) }}
    <table>
    @foreach($files_list as $file)
        <tr>
            <td><a href="{{ action('DriveController@singleFile', $file->id) }}">{{ $file->name  }}</a></td>
            <td><a href="{{ action('DriveController@singleFile', $file->id) }}">{{ $file->mimeType }}</a></td>
            <td><a href="{{ action('DriveController@singleFile', $file->id) }}">{{ $file->id }}</a></td>
        </tr>
    @endforeach
    </table>
    </div>
@endsection