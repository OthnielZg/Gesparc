<x-app-layout>
    <!-- Welcome Header matching mockup -->
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Bonjour {{ Auth::user()->prenom }} !</h1>
        <p class="text-gray-500 font-bold mt-1">Voici le résumé de votre activité sur le parc informatique.</p>
    </div>

    <!-- Stat Cards Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @if(Auth::user()->role === 'agent')
            <!-- Premium Card: Mes Matériels -->
            <div class="bg-white p-6 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="bg-ci-green text-white p-2 rounded-xl shadow-md shadow-ci-green/30">
                                <i class="fa-solid fa-laptop"></i>
                            </div>
                            <span class="text-sm font-black text-gray-500 tracking-wide uppercase">Mes Matériels</span>
                        </div>
                        <h3 class="text-4xl font-black text-gray-900">{{ $stats['mes_materiels'] }}</h3>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">En ma possession</p>
                    </div>
                </div>
            </div>

            <!-- Premium Card: Mes Demandes -->
            <div class="bg-white p-6 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="bg-ci-orange text-white p-2 rounded-xl shadow-md shadow-ci-orange/30">
                                <i class="fa-solid fa-file-circle-check"></i>
                            </div>
                            <span class="text-sm font-black text-gray-500 tracking-wide uppercase">Mes Demandes</span>
                        </div>
                        <h3 class="text-4xl font-black text-gray-900">{{ $stats['mes_demandes'] }}</h3>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Historique personnel</p>
                    </div>
                </div>
            </div>
        @elseif(Auth::user()->role === 'chef_cdi')
            <!-- Premium Card: Matériel du Service -->
            <div class="bg-white p-6 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="bg-indigo-500 text-white p-2 rounded-xl shadow-md shadow-indigo-500/30">
                                <i class="fa-solid fa-building-user"></i>
                            </div>
                            <span class="text-sm font-black text-gray-500 tracking-wide uppercase">Mon Service</span>
                        </div>
                        <h3 class="text-4xl font-black text-gray-900">{{ $stats['materiels_service'] }}</h3>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Équipements du service</p>
                    </div>
                </div>
            </div>

            <!-- Premium Card: Mes Matériels -->
            <div class="bg-white p-6 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="bg-ci-green text-white p-2 rounded-xl shadow-md shadow-ci-green/30">
                                <i class="fa-solid fa-laptop"></i>
                            </div>
                            <span class="text-sm font-black text-gray-500 tracking-wide uppercase">Mes Matériels</span>
                        </div>
                        <h3 class="text-4xl font-black text-gray-900">{{ $stats['mes_materiels'] }}</h3>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">En ma possession</p>
                    </div>
                </div>
            </div>

            <!-- Premium Card: Mes Demandes -->
            <div class="bg-white p-6 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="bg-ci-orange text-white p-2 rounded-xl shadow-md shadow-ci-orange/30">
                                <i class="fa-solid fa-file-circle-check"></i>
                            </div>
                            <span class="text-sm font-black text-gray-500 tracking-wide uppercase">Mes Demandes</span>
                        </div>
                        <h3 class="text-4xl font-black text-gray-900">{{ $stats['mes_demandes'] }}</h3>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Historique personnel</p>
                    </div>
                </div>
            </div>
        @else
            <!-- Rôles Administratifs / Validation -->
            
            @if(Auth::user()->hasRole(['gestionnaire', 'DSI']))
                <!-- Premium Card 1: Total Parc -->
                <div class="bg-white p-6 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 hover:-translate-y-1 transition-transform duration-300">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center space-x-2 mb-4">
                                <div class="bg-ci-orange text-white p-2 rounded-xl shadow-md shadow-ci-orange/30">
                                    <i class="fa-solid fa-laptop"></i>
                                </div>
                                <span class="text-sm font-black text-gray-500 tracking-wide uppercase">Total Parc</span>
                            </div>
                            <h3 class="text-4xl font-black text-gray-900">{{ $stats['total_materiels'] }}</h3>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Unités enregistrées</p>
                        </div>
                        <!-- Mini sparkline placeholder -->
                        <div class="h-12 w-16 opacity-30 flex items-end justify-between space-x-1">
                            <div class="w-full bg-ci-orange rounded-full h-1/3"></div>
                            <div class="w-full bg-ci-orange rounded-full h-2/3"></div>
                            <div class="w-full bg-ci-orange rounded-full h-3/4"></div>
                            <div class="w-full bg-ci-orange rounded-full h-full"></div>
                        </div>
                    </div>
                </div>

                <!-- Premium Card 2: Disponible -->
                <div class="bg-white p-6 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 hover:-translate-y-1 transition-transform duration-300">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center space-x-2 mb-4">
                                <div class="bg-ci-green-dark text-white p-2 rounded-xl shadow-md shadow-green-900/20">
                                    <i class="fa-solid fa-check-circle"></i>
                                </div>
                                <span class="text-sm font-black text-gray-500 tracking-wide uppercase">Disponible</span>
                            </div>
                            <h3 class="text-4xl font-black text-gray-900">{{ $stats['disponible'] }}</h3>
                            <p class="text-[10px] font-bold text-ci-green-dark uppercase tracking-widest mt-1"><i class="fa-solid fa-arrow-trend-up mr-1"></i> Prêts à l'emploi</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Premium Card: Mes Demandes (Visible par tous les admins/validators) -->
            @if(!Auth::user()->isAdmin())
            <div class="bg-white p-6 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="bg-ci-orange text-white p-2 rounded-xl shadow-md shadow-ci-orange/30">
                                <i class="fa-solid fa-file-circle-check"></i>
                            </div>
                            <span class="text-sm font-black text-gray-500 tracking-wide uppercase">Mes Demandes</span>
                        </div>
                        <h3 class="text-4xl font-black text-gray-900">{{ $stats['mes_demandes'] }}</h3>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Historique personnel</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Premium Card 3: À Valider -->
            @if(isset($stats['demandes_a_valider']))
            <div class="bg-white p-6 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="bg-ci-green text-white p-2 rounded-xl shadow-md shadow-ci-green/30">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </div>
                            <span class="text-sm font-black text-gray-500 tracking-wide uppercase">À Valider</span>
                        </div>
                        <h3 class="text-4xl font-black text-gray-900">{{ $stats['demandes_a_valider'] }}</h3>
                        <p class="text-[10px] font-bold text-ci-orange uppercase tracking-widest mt-1">Actions requises</p>
                    </div>
                </div>
            </div>
            @elseif(isset($stats['total_users']))
            <div class="bg-white p-6 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 hover:-translate-y-1 transition-transform duration-300">
                 <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="bg-slate-800 text-white p-2 rounded-xl shadow-md shadow-slate-900/20">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <span class="text-sm font-black text-gray-500 tracking-wide uppercase">Comptes</span>
                        </div>
                        <h3 class="text-4xl font-black text-gray-900">{{ $stats['total_users'] }}</h3>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Utilisateurs actifs</p>
                    </div>
                </div>
            </div>
            @endif
        @endif
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Large Chart Area Placeholder (Col span 2) -->
        <div class="lg:col-span-2 bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-gray-900 tracking-tight">Vue d'ensemble du parc</h3>
                <div class="flex space-x-2">
                    <span class="flex items-center text-xs font-bold text-gray-500"><div class="w-3 h-3 rounded-full bg-ci-green mr-2"></div> Affectés</span>
                    <span class="flex items-center text-xs font-bold text-gray-500"><div class="w-3 h-3 rounded-full bg-ci-orange mr-2"></div> En stock</span>
                </div>
            </div>
            <!-- Fake Chart Bars -->
            <div class="h-64 flex items-end justify-between px-2 pt-10 border-b border-gray-100 pb-2">
                @for($i = 0; $i < 10; $i++)
                    <div class="flex space-x-1 items-end h-full">
                        <div class="w-4 bg-ci-green-dark rounded-t-md opacity-80" style="height: {{ rand(40, 100) }}%"></div>
                        <div class="w-4 bg-ci-orange rounded-t-md opacity-90" style="height: {{ rand(10, 50) }}%"></div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Quick Actions Column -->
        <div class="flex flex-col space-y-6">
            <h3 class="text-xl font-black text-gray-900 tracking-tight pl-2">Actions Rapides</h3>
            
            @unless(Auth::user()->hasRole(['admin', 'gestionnaire']))
            <a href="{{ route('demandes.create') }}" class="relative overflow-hidden bg-ci-green-dark p-6 rounded-[2rem] shadow-xl shadow-green-900/20 hover:scale-[1.02] transition-transform group block">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <h4 class="text-xl font-black text-white mb-1">Nouvelle<br>Demande</h4>
                        <span class="text-[10px] font-bold text-white/60 uppercase tracking-widest">Signaler un besoin</span>
                    </div>
                    <div class="h-12 w-12 bg-white text-ci-green-dark rounded-2xl flex items-center justify-center shadow-lg group-hover:rotate-12 transition-transform">
                        <i class="fa-solid fa-plus text-xl"></i>
                    </div>
                </div>
            </a>
            @endunless

            @if(Auth::user()->hasRole(['admin']))
            <a href="{{ route('admin.users.create') }}" class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.03)] hover:border-ci-orange hover:shadow-md transition-all group flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-black text-gray-900 mb-1">Ajouter Profil</h4>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Gestion des accès</span>
                </div>
                <div class="h-10 w-10 bg-orange-50 text-ci-orange rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
            </a>
            @endif

            @if(Auth::user()->hasRole(['admin', 'gestionnaire']))
            <a href="{{ route('materiels.index') }}" class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.03)] hover:border-ci-green hover:shadow-md transition-all group flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-black text-gray-900 mb-1">Consulter Inventaire</h4>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Liste complète</span>
                </div>
                <div class="h-10 w-10 bg-green-50 text-ci-green rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-desktop"></i>
                </div>
            </a>
            @endif
        </div>
    </div>
</x-app-layout>
