<!DOCTYPE html>
<html lang="{{ config('app.locale')  }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link type="text/css" rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body class="horizontal-nav fixed-layout skin-blue-dark">

<div id="app">
    <app-component></app-component>
</div>

<script src="{{ mix('js/app.js') }}"></script>
@include('ckfinder::setup')
</body>
</html>
