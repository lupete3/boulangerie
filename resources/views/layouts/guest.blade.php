<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ISC Goma') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .bg {
                background-image: url('{{asset("assets/backend/img/logo.jpg")}}');
                /* spécifiez la taille et le comportement de l'image de fond */
                background-size: cover; /* ajuster la taille de l'image pour couvrir le conteneur */
                background-position: center; /* positionner l'image au centre */
                background-repeat: no-repeat; /* ne pas répéter l'image */
                /* autres styles */
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="bg min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 " >
            
            <div>
                <a href="/">

                    {{-- <img src="{{ asset('public/logo_isc.PNG' )}}" width="100" alt=""> --}}
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 shadow-md overflow-hidden sm:rounded-lg" > 
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
