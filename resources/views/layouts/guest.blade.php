<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Subject Loader') }}</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo1.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[url('/public/assets/bg3.jpg')] bg-cover bg-no-repeat">

            <div x-data="{ 
                phrases: ['GOD FEARING', 'RECIPROCATING', 'COMMITTING TO EXCELLENCE'], 
                index: 0 
            }" 
            x-init="setInterval(() => { index = (index + 1) % phrases.length }, 3000)"
            class="flex justify-center items-center h-15">
                
                <div x-init="autoAnimate($el, { duration: 800, easing: 'ease-in-out' })">
                    <h1 :key="phrases[index]" 
                        x-text="phrases[index]" 
                        class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-red-500 via-red-700 to-pink-500 tracking-tighter uppercase">
                    </h1>
                </div>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-lg overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
