<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Kahoot Scores</title>

    <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
    <script src="{{ elixir('js/app.js') }}"></script>
</head>
<body>
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <div class="top-right links">
            <a href="{{ url('/') }}">Leaderboard</a>
            @if (Auth::check())
                &nbsp;/&nbsp;<a href="{{ url('/excel') }}">Upload</a>
            @else
                &nbsp;/&nbsp;<a href="{{ url('/login') }}">Login</a>
                &nbsp;/&nbsp;<a href="{{ url('/register') }}">Register</a>
            @endif
        </div>
    @endif

    @yield('content')
</div>
</body>
</html>
