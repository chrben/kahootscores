@extends('drive.template')

@section('content')
    <h3>Upload Kahoot Results</h3>
    <form method="post" action="{{ action('ExcelController@uploadSheet') }}" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <input name="sheet" type="file" id="fileinput" />
        <button type="submit">Upload</button>
    </form>
    @foreach ($errors as $error)
        <div>$error</div>
    @endforeach
@endsection