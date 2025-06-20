<!DOCTYPE html>
<html lang="en">

<!-- main layout file -->
<head>
 
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Bloggit')</title>

    <!-- Fonts -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->

    @livewireStyles
    <style>
        body {
            font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;
        }

        h1, h2, h3, h4, h5, h6
        {
            font-family: 'IBM Plex Sans', sans-serif;
            font-weight: 500;
        }

        .comments-section {
            display: none;
        }

        .comments-section.loaded {
            display: block;
        }

        .post-title {
            font-family: 'IBM Plex Sans', sans-serif;
            font-weight: 500;
            font-size: 1 rem;
            line-height: 1.4;
        }

        .post-content {
            font-family: 'IBM Plex Sans', sans-serif;
            font-weight: 400;
            color: #555555;
            font-size: 0.75rem;
            line-height: 1.6;
        }
    </style>

</head>



<body>
    <div class="min-h-screen flex flex-col">
        @include('partials.navigation')
        <div class="flex flex-grow">
            @include('partials.sidebar')

            <div class="flex-1 bg-white dark:bg-gray-800">
                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white dark:bg-gray-800 shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <div class="flex-1 bg-white dark:bg-gray-800 lg:ml-64 relative">
                    <main class="flex flex-col md:flex-row">
                        <div class="w-full md:w-2/3 lg:w-3/4 p-4">
                            <!-- Page Content -->
                            @yield('content')
                            @if ($errors->any())
                                <div role="alert" class="rounded border-s-4 border-red-500 bg-red-50 p-4">
                                    <div class="flex items-center gap-2 text-red-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            class="size-5">
                                            <path fill-rule="evenodd"
                                                d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z"
                                                clip-rule="evenodd" />
                                        </svg>

                                        <strong class="block font-medium">Something went wrong</strong>
                                    </div>

                                    <p class="mt-2 text-sm text-red-700">
                                    <div>
                                        Errors:
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    </p>
                                </div>
                            @endif

                            @if (session('message'))
                                <p><b>{{ session('message') }}</b></p>
                            @endif
                        </div>
                        
                        <!-- Information bar -->
                        @if (!View::hasSection('infobar_exclude'))
                            <div class="hidden md:block md:w-1/4 lg:w-1/5 p-4 md:sticky md:top-20 md:h-[calc(100vh-10rem)] md:overflow-y-auto mr-48 bg-gray-100 mb-10 mt-5 rounded-lg">
                                @yield('infobar')
                            </div>
                        @endif

                    </main>
                </div>
            </div>
        </div>
    </div>

    @yield('scripts')
    @livewireScripts

    @auth
        <script> 
            document.addEventListener('livewire:initialized', () => { 
                window.Echo.private(`App.Models.User.{{ auth()->id() }}`)  //listen to notification events
                    .notification((notification) => {
                        Livewire.dispatch('refreshNotifications'); 
                    });
            });
        </script>
    @endauth
</body>

</html>
