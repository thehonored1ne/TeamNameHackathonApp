<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Faculty Load Assignment System') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col items-center justify-center p-6">

        {{-- Header Nav --}}
        <header class="w-full max-w-5xl flex items-center justify-between mb-12">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <span class="font-semibold text-lg text-gray-800">Faculty Load Assignment System</span>
            </div>
            @auth
                <a href="{{ auth()->user()->role === 'program_chair' ? route('chair.dashboard') : route('teacher.dashboard') }}"
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                    Log In
                </a>
            @endauth
        </header>

        {{-- Hero Section --}}
        <main class="w-full max-w-5xl">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                {{-- Left: Text Content --}}
                <div>
                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full mb-4">
                        AI-Powered Scheduling
                    </span>
                    <h1 class="text-4xl font-bold text-gray-900 leading-tight mb-4">
                        Automated Faculty Load Assignment System
                    </h1>
                    <p class="text-gray-500 text-base mb-6 leading-relaxed">
                        Eliminate hours of manual scheduling. Our system automatically matches teachers to subjects based on expertise and availability — with zero conflicts.
                    </p>

                    <div class="flex gap-3">
                        @auth
                            <a href="{{ auth()->user()->role === 'program_chair' ? route('chair.dashboard') : route('teacher.dashboard') }}"
                                class="px-6 py-3 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                                Go to Dashboard →
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="px-6 py-3 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                                Get Started →
                            </a>
                        @endauth
                    </div>
                </div>

                {{-- Right: Feature Cards --}}
                <div class="grid grid-cols-1 gap-4">

                    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-start gap-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm mb-1">AI-Powered Matching</h3>
                            <p class="text-gray-500 text-xs leading-relaxed">TF-IDF algorithm matches teachers to subjects by expertise first, then availability.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-start gap-4">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm mb-1">Zero Conflicts Guaranteed</h3>
                            <p class="text-gray-500 text-xs leading-relaxed">Automatic conflict detection ensures no teacher is double-booked across schedules.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-start gap-4">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm mb-1">Load Assignment Report</h3>
                            <p class="text-gray-500 text-xs leading-relaxed">Generate and export complete reports with rationale, overload flags, and teacher summaries.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-start gap-4">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm mb-1">Role-Based Access</h3>
                            <p class="text-gray-500 text-xs leading-relaxed">Program Chairs manage assignments while Teachers view their schedules and send requests.</p>
                        </div>
                    </div>

                </div>
            </div>
        </main>

        {{-- Footer --}}
        <footer class="mt-16 text-center text-xs text-gray-400">
            Faculty Load Assignment System — Built for Global Reciprocal Colleges
        </footer>

    </body>
</html>