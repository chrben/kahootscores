<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #232a3a;
            color: #e8e8e8;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
            font-size: 2vmin;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
        a {
            color: #e8e8e8;
            text-decoration: none;
        }
        div.lb_entry > a:hover {
            -webkit-transform: scale(2.2);
            -moz-transform: scale(2.2);
            -ms-transform: scale(2.2);
            -o-transform: scale(2.2);
            transform: scale(2.2);
        }
        div.lb_entry > a  {
            font-size: 1.2em;
            color: #929292;
        }
        div.lb_place_1 > a   {
            font-size: 3em;
            color: #ae8241;
        }
        div.lb_place_2 > a  {
             font-size: 2.5em;
             color: #ababab;
         }
        div.lb_place_3 > a {
              font-size: 1.9em;
              color: #a96d5b;
          }
        div.lb_upload_link {
            margin-top: 3em;
            font-size: 0.6em;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <div class="top-right links">
            @if (Auth::check())
                <a href="{{ url('/home') }}">Home</a>
            @else
                <a href="{{ url('/login') }}">Login</a>
                <a href="{{ url('/register') }}">Register</a>
            @endif
        </div>
    @endif

    <div class="content">
        @yield('content')
    </div>
</div>
</body>
</html>
