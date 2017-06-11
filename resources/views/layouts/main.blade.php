<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">

    <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="http://www.eurocomputer.Fr">{{ config('app.name', 'Laravel') }}</a>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="{{route('getSearch')}}">Home <span class="sr-only">(current)</span></a>
                </li>

            </ul>
            <form action="{{route('postSearch')}}" method="POST" class="form-inline my-2 my-lg-0">
                {{ csrf_field() }}
                <input name="search" class="form-control mr-sm-2" type="text" placeholder="Imprimante ZM400">
                <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Recherche</button>
            </form>
        </div>
    </nav>

    <div class="container-fluid">
        <br>
        <div class="row">
            @yield('content')
        </div>
    </div>


</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
