<header class="z-30 border-b border-white/10 shadow-md sticky top-0 transition-all duration-300"
        x-data="{ isScrolled: false }"
        @scroll.window="isScrolled = (window.pageYOffset > 10) ? true : false"
        :class="isScrolled ? 'bg-ci-green-dark/40 backdrop-blur-xl' : 'bg-ci-green-dark'">
    <div class="h-16 pl-0 pr-4 sm:pr-8 flex items-center justify-between">
        <!-- Left Side: Menu Toggle & Brand Logo -->
        <div class="flex items-center">
            
            <!-- Mobile Menu Toggle (Visible only on small screens) -->
            <button type="button" 
                    @click="sidebarOpen = !sidebarOpen"
                    class="md:hidden mr-4 p-2 rounded-xl text-white/70 hover:text-white hover:bg-white/10 focus:outline-none transition-colors border border-transparent hover:border-white/10">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>

                <!-- Full Brand Logo Only -->
                <div class="flex items-center w-auto">
                    <img src="{{ asset('images/logo_dgi.png') }}" alt="Logo DGI" class="w-64 sm:w-80 max-h-12 object-contain">
                </div>
        </div>

        <!-- Right Actions & Profile -->
        <div class="flex items-center space-x-6">
            <!-- Search Bar placeholder like TailAdmin -->
            <div class="hidden lg:flex items-center bg-white/10 border border-white/5 rounded-full px-4 py-1.5 mr-2">
                <i class="fa-solid fa-magnifying-glass text-white/50 text-xs"></i>
                <input type="text" placeholder="Recherche rapide..." class="bg-transparent border-none focus:ring-0 text-xs text-white w-48 placeholder-white/40">
            </div>

            <!-- Notifications -->
            <button class="relative p-2 text-white/70 hover:text-white transition-colors group">
                <i class="fa-solid fa-bell text-xl"></i>
                <span class="absolute top-1.5 right-1.5 block w-2.5 h-2.5 rounded-full bg-ci-orange border-2 border-ci-green-dark"></span>
            </button>

            <!-- Profile Dropdown -->
            <div class="relative flex items-center pl-2" x-data="{ dropdownOpen: false }" @click.away="dropdownOpen = false">
                <!-- Dropdown Trigger -->
                <button @click="dropdownOpen = !dropdownOpen" class="flex items-center cursor-pointer group focus:outline-none transition-all duration-300 hover:scale-[1.02]">
                    <div class="text-right mr-3 hidden sm:block">
                        <p class="text-sm font-black text-white leading-none transition-colors duration-300">{{ Auth::user()->full_name }}</p>
                        <p class="text-[10px] font-bold text-white/50 uppercase tracking-widest mt-1 transition-colors duration-300">{{ Auth::user()->getRoleLabel() }}</p>
                    </div>
                    <div class="relative">
                        <!-- Profile Image Placeholder (will be replaced by actual image later) -->
                        <div class="h-10 w-10 rounded-full bg-white text-ci-green-dark flex items-center justify-center font-black text-lg shadow-sm group-hover:shadow-md transition-all duration-300 overflow-hidden border-2 border-transparent">
                            @if(isset(Auth::user()->photo_path) && Auth::user()->photo_path)
                                <img src="{{ asset('storage/' . Auth::user()->photo_path) }}" alt="Profile" class="h-full w-full object-cover">
                            @else
                                {{ substr(Auth::user()->prenom, 0, 1) }}{{ substr(Auth::user()->nom, 0, 1) }}
                            @endif
                        </div>
                    </div>
                    <!-- Animated Chevron -->
                    <i class="fa-solid fa-chevron-down text-[10px] text-white/50 ml-3 group-hover:text-white transition-transform duration-200 hidden sm:block" 
                       :class="dropdownOpen ? 'rotate-180' : ''"></i>
                </button>

                <!-- Dropdown Menu (TailAdmin Style) -->
                <div x-show="dropdownOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-1"
                     class="absolute right-0 top-full mt-4 w-64 bg-white rounded-2xl shadow-[0px_10px_40px_rgba(0,0,0,0.08)] border border-gray-100 py-4 z-50 text-gray-700 font-medium"
                     x-cloak>
                     
                     <!-- User Info Header -->
                     <div class="px-6 pb-4 mb-2 border-b border-gray-100">
                         <p class="text-sm font-black text-gray-900 leading-tight">{{ Auth::user()->full_name }}</p>
                         <p class="text-xs text-gray-400 mt-1">{{ Auth::user()->email }}</p>
                     </div>

                     <!-- Links -->
                     <ul class="flex flex-col">
                         <li>
                             <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-6 py-2.5 hover:bg-gray-50 hover:text-ci-orange transition-colors">
                                 <i class="fa-regular fa-user text-lg text-gray-400 w-5 text-center"></i>
                                 Modifier le profil
                             </a>
                         </li>
                     </ul>

                     <div class="h-[1px] bg-gray-100 my-2 mx-6"></div>

                     <!-- Logout Trigger -->
                     <div class="px-2">
                         <button @click="showLogoutModal = true; dropdownOpen = false" class="flex items-center gap-3 px-6 py-2.5 w-full text-left rounded-xl hover:bg-gray-50 hover:text-red-500 transition-colors group">
                             <i class="fa-solid fa-arrow-right-from-bracket text-lg text-gray-400 w-5 text-center group-hover:text-red-500 transition-colors"></i>
                             Se déconnecter
                         </button>
                     </div>
                </div>
            </div>
        </div>
    </div>
</header>
