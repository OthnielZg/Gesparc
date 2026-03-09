<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-ci-bg">
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
            document.documentElement.style.setProperty('--ci-green', '#044C26');
            document.documentElement.style.setProperty('--ci-green-light', '#076835');
            document.documentElement.style.setProperty('--ci-orange', '#EE6B12');
        </script>
        
        <style>
            [x-cloak] { display: none !important; }
            .sidebar-link-active { background-color: rgba(255, 255, 255, 0.1); border-left: 4px solid #EE6B12; }
            /* Scrollbar de la Sidebar */
            .sidebar-scroll {
                scrollbar-width: thin;
                scrollbar-color: transparent transparent;
            }
            .sidebar-scroll:hover {
                scrollbar-color: rgba(255,255,255,0.12) transparent;
            }
            .sidebar-scroll::-webkit-scrollbar {
                width: 5px;
            }
            .sidebar-scroll::-webkit-scrollbar-track {
                background: transparent;
            }
            .sidebar-scroll::-webkit-scrollbar-thumb {
                background: transparent;
                border-radius: 10px;
                transition: background 0.3s;
            }
            .sidebar-scroll:hover::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.12);
            }
            .sidebar-scroll:hover::-webkit-scrollbar-thumb:hover {
                background: rgba(255, 255, 255, 0.25);
            }
        </style>
    </head>
    <body class="font-sans antialiased h-full overflow-hidden text-gray-800" x-data="{ sidebarOpen: false, desktopSidebarOpen: true, scrolled: false, showLogoutModal: false }">
        <div class="flex flex-col h-screen bg-ci-bg">
            
            <!-- Topbar (Now on top of everything) -->
            @include('layouts.topbar')

            <!-- Main Body (Sidebar + Content) -->
            <div class="flex flex-1 overflow-hidden">
                <!-- Sidebar Desktop -->
                <aside class="hidden md:flex md:flex-shrink-0 z-40 relative h-full transition-all duration-300"
                       x-show="desktopSidebarOpen"
                       x-transition:enter="transition-all ease-out duration-300"
                       x-transition:enter-start="-ml-[260px]"
                       x-transition:enter-end="ml-0"
                       x-transition:leave="transition-all ease-in duration-300"
                       x-transition:leave-start="ml-0"
                       x-transition:leave-end="-ml-[260px]"
                >
                    <div class="flex flex-col w-[260px] bg-ci-green-dark shadow-xl overflow-y-auto sidebar-scroll w-full">
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
                     class="fixed inset-0 z-40 md:hidden flex" 
                     @click.away="sidebarOpen = false"
                     x-cloak>
                    <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm"></div>
                    <!-- Sidebar Panel -->
                    <div class="relative flex flex-col w-[260px] bg-ci-green-dark h-full sidebar-scroll overflow-y-auto z-10">
                        @include('layouts.sidebar')
                    </div>
                    <!-- Close Button (outside overflow container) -->
                    <div class="relative pt-2 pl-2 z-10">
                        <button type="button" 
                                @click="sidebarOpen = false"
                                class="flex items-center justify-center h-10 w-10 rounded-full bg-white/10 hover:bg-white/20 focus:outline-none transition-colors">
                            <i class="fa-solid fa-xmark text-white text-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- Main Content Area -->
                <main class="flex-1 relative overflow-y-auto focus:outline-none">
                    <div class="py-6 px-4 sm:px-6 lg:px-8">
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

        <!-- Logout Confirmation Modal -->
        <div x-show="showLogoutModal" 
             class="fixed inset-0 z-[100] overflow-y-auto" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak>
            
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div @click.away="showLogoutModal = false" 
                     class="relative transform overflow-hidden rounded-[2.5rem] bg-white p-8 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <div class="flex items-center justify-center mb-6">
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-red-50 text-red-500">
                            <i class="fa-solid fa-arrow-right-from-bracket text-3xl"></i>
                        </div>
                    </div>

                    <div class="text-center">
                        <h3 class="text-2xl font-black text-gray-900 mb-2">Déconnexion</h3>
                        <p class="text-gray-500 font-medium px-4">Êtes-vous sûr de vouloir quitter votre session Gesparc ?</p>
                    </div>

                    <div class="mt-10 flex flex-col sm:flex-row gap-4">
                        <button type="button" 
                                @click="showLogoutModal = false"
                                class="flex-1 px-6 py-4 rounded-2xl bg-gray-100 text-gray-700 font-black hover:bg-gray-200 transition-colors uppercase tracking-widest text-xs">
                            Annuler
                        </button>
                        <form method="POST" action="{{ route('logout') }}" class="flex-1">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-6 py-4 rounded-2xl bg-red-600 text-white font-black shadow-lg shadow-red-200 hover:bg-red-700 transition-all uppercase tracking-widest text-xs">
                                Oui, me déconnecter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>
