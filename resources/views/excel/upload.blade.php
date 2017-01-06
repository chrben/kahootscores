@extends('drive.template')

@section('content')
    <div class="row">
        <div class="col-xs-12 col-md-10 col-md-offset-1">
            <form class="form-horizontal" method="post" action="{{ action('ExcelController@uploadSheet') }}" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class="fieldset">
                    <legend>Upload Kahoot Results</legend>
                    <div class="form-group">
                        @if ($errors->has('sheet'))
                            <div class="input-group has-error">
                        @else
                            <div class="input-group">
                        @endif
                            <label class="input-group-addon btn btn-default btn-file">
                                Browse&hellip; <input name="sheet" type="file" style="display: none;"/>
                            </label>
                            <input class="form-control" type="text" readonly />
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit">Upload</button>
                            </span>
                        </div>
                    </div>
                </div>
            </form>
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
            $(document).on('change', ':file', function () {
                var input = $(this),
                        numFiles = input.get(0).files ? input.get(0).files.length : 1,
                        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                input.trigger('fileselect', [numFiles, label]);
            });
            $(document).ready( function() {
                $(':file').on('fileselect', function(event, numFiles, label) {

                    var input = $(this).parents('.input-group').find(':text'),
                            log = numFiles > 1 ? numFiles + ' files selected' : label;

                    if( input.length ) {
                        input.val(log);
                    } else {
                        if( log ) alert(log);
                    }

                });
            });
        });
    </script>
@endsection