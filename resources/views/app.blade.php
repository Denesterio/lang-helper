<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Lang-helper</title>

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/js/app.js'])
        @else
            <style>
            </style>
        @endif
    </head>
    <body>
        @php
            $user = Auth::user();
        @endphp
        <div id="app">
            <user-home :user="{{ $user }}" />
        </div>
    </body>
</html>
