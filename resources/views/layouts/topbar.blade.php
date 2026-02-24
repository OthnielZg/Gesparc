<header class="bg-white border-b border-gray-200 z-30">
    <div class="px-4 sm:px-6 md:px-8">
        <div class="flex justify-between h-16">
            <!-- Mobile Menu Toggle -->
            <div class="flex md:hidden">
                <button type="button" 
                        @click="sidebarOpen = true"
                        class="px-4 text-gray-500 border-r border-gray-200 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-ci-green">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>

            <!-- Page Title (Breadcrumb or Simple) -->
            <div class="flex-1 flex items-center px-4 md:px-0">
                <div class="hidden md:flex items-center text-sm font-medium text-gray-500 uppercase tracking-widest">
                    <span class="text-ci-green font-black">GESPARC</span>
                    <i class="fa-solid fa-chevron-right mx-3 text-[8px]"></i>
                    <span class="text-gray-900 font-black">{{ $title ?? 'Tableau de Bord' }}</span>
                </div>
            </div>

            <!-- Header Actions -->
            <div class="ml-4 flex items-center md:ml-6 space-x-4">
                <!-- Notifications -->
                <button class="relative p-2 text-gray-400 hover:text-gray-500 focus:outline-none group">
                    <i class="fa-solid fa-bell text-lg"></i>
                    <span class="absolute top-1 right-1 block h-2.5 w-2.5 rounded-full bg-ci-orange border-2 border-white ring-2 ring-transparent group-hover:ring-ci-orange/20 transition-all"></span>
                </button>

                <!-- Search (Placeholder) -->
                <button class="p-2 text-gray-400 hover:text-gray-500 focus:outline-none hidden sm:block">
                    <i class="fa-solid fa-magnifying-glass text-lg"></i>
                </button>

                <!-- Vertical Divider -->
                <div class="h-6 w-px bg-gray-200 mx-2"></div>

                <!-- Date display -->
                <div class="hidden lg:flex flex-col items-end mr-2">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ now()->translatedFormat('l') }}</span>
                    <span class="text-xs font-bold text-gray-900">{{ now()->translatedFormat('d F Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</header>
