<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'GESPARC') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <script>
            document.documentElement.style.setProperty('--ci-green', '#009E60');
            document.documentElement.style.setProperty('--ci-orange', '#F77F00');
        </script>
        
        <style>
            [x-cloak] { display: none !important; }
            .bg-ci-green { background-color: #009E60; }
            .bg-ci-orange { background-color: #F77F00; }
            .text-ci-green { color: #009E60; }
            .text-ci-orange { color: #F77F00; }
            .sidebar-link-active { background-color: rgba(255, 255, 255, 0.1); border-left: 4px solid #F77F00; }
        </style>
    </head>
    <body class="font-sans antialiased h-full overflow-hidden" x-data="{ sidebarOpen: false }">
        <div class="flex h-screen bg-gray-100">
            
            <!-- Sidebar Desktop -->
            <aside class="hidden md:flex md:flex-shrink-0">
                <div class="flex flex-col w-64 bg-slate-900 shadow-2xl">
                    @include('layouts.sidebar')
                </div>
            </aside>

            <!-- Sidebar Mobile -->
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition-opacity ease-linear duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="transition-opacity ease-linear duration-300" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-40 md:hidden" 
                 @click.away="sidebarOpen = false"
                 x-cloak>
                <div class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>
                <div class="relative flex-1 flex flex-col max-w-xs w-full bg-slate-900 pt-5 pb-4">
                    <div class="absolute top-0 right-0 -mr-12 pt-2">
                        <button type="button" 
                                @click="sidebarOpen = false"
                                class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                            <i class="fa-solid fa-xmark text-white"></i>
                        </button>
                    </div>
                    @include('layouts.sidebar')
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex flex-col w-0 flex-1 overflow-hidden">
                
                <!-- Topbar -->
                @include('layouts.topbar')

                <!-- Main Content -->
                <main class="flex-1 relative overflow-y-auto focus:outline-none">
                    <div class="py-6 px-4 sm:px-6 md:px-8">
                        @if (isset($header))
                            <header class="mb-8">
                                {{ $header }}
                            </header>
                        @endif

                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
