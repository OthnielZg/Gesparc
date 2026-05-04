<x-app-layout>
    <!-- En-tête de bienvenue adapté au rôle -->
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-800 tracking-tight uppercase leading-none">
            Bonjour, {{ Auth::user()->prenom }}
            @if(Auth::user()->role === 'DR')
                <span class="text-ci-green">&nbsp;&mdash; Directeur Régional</span>
            @elseif(Auth::user()->role === 'gestionnaire')
                <span class="text-ci-green">&nbsp;&mdash; Gestionnaire</span>
            @elseif(Auth::user()->role === 'DSI')
                <span class="text-ci-green">&nbsp;&mdash; DSI</span>
            @elseif(Auth::user()->role === 'SDGRTE')
                <span class="text-ci-green">&nbsp;&mdash; SDGRTE</span>
            @elseif(Auth::user()->role === 'chef_cdi')
                <span class="text-ci-green">&nbsp;&mdash; Chef CDI</span>
            @endif
        </h1>
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mt-2">
            @if(Auth::user()->role === 'DR') Vue de pilotage régional
            @elseif(Auth::user()->role === 'gestionnaire') Suivi du stock et des demandes
            @elseif(in_array(Auth::user()->role, ['DSI', 'SDGRTE'])) Validation des dossiers en attente
            @elseif(Auth::user()->role === 'chef_cdi') Suivi du service et de vos demandes
            @else Résumé de votre activité sur le parc informatique
            @endif
        </p>
    </div>

    <!-- Ligne des Cartes Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ Auth::user()->role === 'agent' ? '3' : '4' }} gap-6 mb-8">
        @if(Auth::user()->role === 'agent')
            <!-- Carte Premium : Mes Matériels -->
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

            <!-- Carte Premium : Mes Demandes -->
            <div class="bg-white p-6 rounded-[2rem] shadow-[0_8px_30_rgb(0,0,0,0.04)] border border-gray-100 hover:-translate-y-1 transition-transform duration-300">
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

            <!-- Carte Action : Nouvelle Demande -->
            <a href="{{ route('demandes.create') }}" class="relative overflow-hidden bg-ci-green-dark p-6 rounded-[2rem] shadow-xl shadow-green-900/20 hover:scale-[1.02] transition-transform group block">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                <div class="relative z-10 flex items-center justify-between h-full">
                    <div>
                        <h4 class="text-xl font-black text-white mb-1">Nouvelle<br>Demande</h4>
                        <span class="text-[10px] font-bold text-white/60 uppercase tracking-widest">Signaler un besoin</span>
                    </div>
                    <div class="h-12 w-12 bg-white text-ci-green-dark rounded-2xl flex items-center justify-center shadow-lg group-hover:rotate-12 transition-transform">
                        <i class="fa-solid fa-plus text-xl"></i>
                    </div>
                </div>
            </a>
        @elseif(Auth::user()->role === 'chef_cdi')
            <!-- Carte Premium : Matériel du Service -->
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

            <!-- Carte Premium : Mes Matériels -->
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

            <!-- Carte Premium : Mes Demandes -->
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
                <!-- Carte Premium 1 : Total Parc -->
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

                <!-- Carte Premium 2 : Disponible -->
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

            <!-- Carte Premium : Mes Demandes (Visible par tous les admins/validateurs) -->
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

            @if(Auth::user()->role === 'DR')
            <!-- Carte Premium : Dossiers en attente (DR) -->
            <div class="bg-white p-6 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 hover:-translate-y-1 transition-transform duration-300 relative group md:col-span-2">
                <a href="{{ route('validations.index') }}" class="absolute inset-0 z-10"></a>
                <div class="flex items-start justify-between h-full">
                    <div class="flex flex-col justify-between h-full w-full">
                        <div class="flex items-center justify-between w-full mb-4">
                            <div class="flex items-center space-x-2">
                                <div class="bg-amber-500 text-white p-2 rounded-xl shadow-md shadow-amber-500/30">
                                    <i class="fa-solid fa-clock"></i>
                                </div>
                                <span class="text-sm font-black text-gray-500 tracking-wide uppercase">Dossiers en attente</span>
                            </div>
                            <!-- Flèche indicative -->
                            <div class="bg-gray-50 text-gray-300 h-8 w-8 flex items-center justify-center rounded-full group-hover:bg-amber-500 group-hover:text-white transition-colors">
                                <i class="fa-solid fa-arrow-right"></i>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <h3 class="text-4xl font-black text-gray-900">{{ $stats['demandes_a_valider'] }}</h3>
                            <p class="text-[10px] font-bold text-amber-500 uppercase tracking-widest mt-1">Action requise</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte Premium : Directions actives (DR) -->
            <div class="bg-white p-6 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 hover:-translate-y-1 transition-transform duration-300">
                <div class="flex items-start justify-between h-full">
                    <div class="w-full flex flex-col justify-between h-full">
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="bg-ci-green text-white p-2 rounded-xl shadow-md shadow-ci-green/30">
                                <i class="fa-solid fa-sitemap"></i>
                            </div>
                            <span class="text-sm font-black text-gray-500 tracking-wide uppercase">Directions actives</span>
                        </div>
                        <div class="flex items-end justify-between w-full mt-auto">
                            <div>
                                <h3 class="text-4xl font-black text-gray-900">{{ $stats['directions_region']->count() }}</h3>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">{{ $stats['nom_ville'] }}</p>
                            </div>
                            @php
                                $saines = $stats['directions_region']->where('health', 'healthy')->count();
                                $total  = $stats['directions_region']->count();
                                $pct    = $total > 0 ? round(($saines / $total) * 100) : 0;
                            @endphp
                            <div class="text-right">
                                <span class="text-xl font-black text-ci-green leading-none">{{ $pct }}%</span>
                                <p class="text-[8px] font-bold text-ci-green uppercase tracking-widest mt-0.5">Opérationnelles</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif


            <!-- Carte Premium 3 : À Valider -->
            @if(isset($stats['demandes_a_valider']) && Auth::user()->role !== 'DR')
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
                <!-- Carte Premium 1 : Total Utilisateurs -->
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
                            <div class="flex items-center space-x-3 mt-1">
                                <span class="text-[10px] font-bold text-ci-green uppercase"><i class="fa-solid fa-circle text-[6px] mr-1"></i> {{ $stats['users_actifs'] }} Actifs</span>
                                <span class="text-[10px] font-bold text-red-500 uppercase"><i class="fa-solid fa-circle text-[6px] mr-1"></i> {{ $stats['users_bloques'] }} Bloqués</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carte Premium 2 : Villes -->
                <div class="bg-white p-6 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 hover:-translate-y-1 transition-transform duration-300">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center space-x-2 mb-4">
                                <div class="bg-ci-orange text-white p-2 rounded-xl shadow-md shadow-ci-orange/30">
                                    <i class="fa-solid fa-city"></i>
                                </div>
                                <span class="text-sm font-black text-gray-500 tracking-wide uppercase">Villes</span>
                            </div>
                            <h3 class="text-4xl font-black text-gray-900">{{ $stats['total_villes'] }}</h3>
                            <p class="text-[10px] font-bold text-ci-orange uppercase tracking-widest mt-1">Implantations</p>
                        </div>
                    </div>
                </div>

                <!-- Carte Premium 3 : Directions -->
                <div class="bg-white p-6 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 hover:-translate-y-1 transition-transform duration-300">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center space-x-2 mb-4">
                                <div class="bg-ci-green text-white p-2 rounded-xl shadow-md shadow-ci-green/30">
                                    <i class="fa-solid fa-sitemap"></i>
                                </div>
                                <span class="text-sm font-black text-gray-500 tracking-wide uppercase">Directions</span>
                            </div>
                            <h3 class="text-4xl font-black text-gray-900">{{ $stats['total_directions'] }}</h3>
                            <p class="text-[10px] font-bold text-ci-green uppercase tracking-widest mt-1">Unités structurelles</p>
                        </div>
                    </div>
                </div>

                <!-- Premium Card 4: Services -->
                <div class="bg-white p-6 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 hover:-translate-y-1 transition-transform duration-300">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center space-x-2 mb-4">
                                <div class="bg-ci-green-dark text-white p-2 rounded-xl shadow-md shadow-green-900/20">
                                    <i class="fa-solid fa-briefcase"></i>
                                </div>
                                <span class="text-sm font-black text-gray-500 tracking-wide uppercase">Services</span>
                            </div>
                            <h3 class="text-4xl font-black text-gray-900">{{ $stats['total_services'] }}</h3>
                            <p class="text-[10px] font-bold text-ci-green-dark uppercase tracking-widest mt-1">Bureaux et Pôles</p>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>



    @if(Auth::user()->role === 'DR')
    <div class="space-y-6">

        <!-- Radar Territorial Enrichi -->
        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-5 bg-slate-50/50 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest flex items-center">
                    <i class="fa-solid fa-radar mr-3 text-ci-green"></i> Pilotage Régional
                    <span class="mx-3 text-gray-200">|</span>
                    <span class="text-gray-400 font-bold">{{ $stats['nom_ville'] }}</span>
                </h3>
                <a href="{{ route('validations.index') }}" class="inline-flex items-center gap-2 px-4 py-1.5 bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest rounded-lg hover:bg-black transition-all">
                    <i class="fa-solid fa-check-double text-[8px]"></i> Valider
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[9px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50">
                            <th class="px-8 py-4">Direction</th>
                            <th class="px-8 py-4">État</th>
                            <th class="px-8 py-4">Dossiers</th>
                            <th class="px-8 py-4">Charge</th>
                            <th class="px-8 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($stats['directions_region'] as $direction)
                        @php
                            $maxAttente = $stats['directions_region']->max('attente_validation') ?: 1;
                            $pctBar     = min(100, round(($direction->attente_validation / $maxAttente) * 100));
                            $barColor   = $direction->health === 'unhealthy' ? 'bg-red-400'
                                        : ($direction->health === 'needs_action' ? 'bg-ci-orange' : 'bg-ci-green');
                        @endphp
                        <tr class="hover:bg-slate-50/40 transition-colors">
                            <td class="px-8 py-4">
                                <span class="text-sm font-bold text-slate-900">{{ $direction->nom_direction }}</span>
                            </td>
                            <td class="px-8 py-4">
                                @if($direction->health === 'unhealthy')
                                    <span class="text-[9px] font-black py-1 px-2.5 bg-red-50 text-red-600 rounded-md border border-red-100 uppercase">Urgent</span>
                                @elseif($direction->health === 'needs_action')
                                    <span class="text-[9px] font-black py-1 px-2.5 bg-orange-50 text-ci-orange rounded-md border border-orange-100 uppercase">À traiter</span>
                                @else
                                    <span class="text-[9px] font-black py-1 px-2.5 bg-green-50 text-ci-green rounded-md border border-green-100 uppercase">OK</span>
                                @endif
                            </td>
                            <td class="px-8 py-4">
                                <span class="text-sm font-black text-slate-700">{{ $direction->attente_validation }}</span>
                            </td>
                            <td class="px-8 py-4 w-40">
                                <div class="w-full bg-gray-100 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full {{ $barColor }} transition-all" style="width: {{ $pctBar }}%"></div>
                                </div>
                            </td>
                            <td class="px-8 py-4 text-right">
                                <a href="{{ route('validations.index') }}" class="text-ci-green font-black text-[10px] uppercase hover:underline">Ouvrir</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2 Charts côte à côte -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Chart 1 : Parc par Catégorie (Bar) -->
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-8 py-4 bg-slate-50/60 border-b border-gray-100">
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Parc par Catégorie</span>
                </div>
                <div class="p-6" style="height:280px">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
            <!-- Chart 2 : État des Directions (Doughnut) -->
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-8 py-4 bg-slate-50/60 border-b border-gray-100">
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">État des Directions</span>
                </div>
                <div class="p-6" style="height:280px">
                    <canvas id="healthChart"></canvas>
                </div>
            </div>
        </div>

    </div>
    @elseif(!in_array(Auth::user()->role, ['agent', 'admin']))
    <!-- Grille de Contenu Principal pour Managers/Validants (Gestionnaire / DSI) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- ... reste identique pour les autres rôles ... -->
        
        <!-- Real Charts Grid (Col span 2) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="grid grid-cols-1 {{ Auth::user()->role === 'gestionnaire' ? '' : 'md:grid-cols-2' }} gap-6">
                @if(Auth::user()->role !== 'gestionnaire')
                <!-- Graphique 1 : Répartition des Demandes (Doughnut) -->
                <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden h-full flex flex-col">
                    <div class="px-8 py-4 bg-slate-50/60 border-b border-gray-100 flex-shrink-0">
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">
                            @if(Auth::user()->role === 'agent') Mes Demandes
                            @elseif(Auth::user()->role === 'chef_cdi') Demandes du Service
                            @elseif(Auth::user()->role === 'DR') Demandes Direction
                            @elseif(Auth::user()->role === 'admin') Répartition par Rôle
                            @else État du Workflow @endif
                        </span>
                    </div>
                    <div class="p-6 flex-1 relative min-h-[250px]">
                        @if(Auth::user()->role === 'admin')
                            <canvas id="adminRoleChart"></canvas>
                        @else
                            @if($repartitionStatut->isEmpty())
                                <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-300">
                                    <i class="fa-solid fa-chart-pie text-4xl mb-2 opacity-20"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Aucune donnée</span>
                                </div>
                            @endif
                            <canvas id="statusChart"></canvas>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Graphique 2 : Stock par Catégorie (Bar) -->
                <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden h-full flex flex-col">
                    <div class="px-8 py-4 bg-slate-50/60 border-b border-gray-100 flex-shrink-0">
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">
                            @if(Auth::user()->role === 'agent') Mes Matériels
                            @elseif(Auth::user()->role === 'chef_cdi') Stock du Service
                            @elseif(Auth::user()->role === 'DR') Stock Direction
                            @elseif(Auth::user()->role === 'admin') Structure Organisationnelle
                            @else Stock par Catégorie @endif
                        </span>
                    </div>
                    <div class="p-6 flex-1 relative min-h-[250px]">
                        @if(Auth::user()->role === 'admin')
                            <canvas id="adminStructureChart"></canvas>
                        @else
                            @if($stockParCategorie->isEmpty())
                                <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-300">
                                    <i class="fa-solid fa-barcode text-4xl mb-2 opacity-20"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Aucune donnée</span>
                                </div>
                            @endif
                            <canvas id="categoryChart"></canvas>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <!-- Colonne Actions Rapides -->
        <div class="flex flex-col space-y-6">
            <h3 class="text-xl font-black text-gray-900 tracking-tight pl-2">Actions Rapides</h3>
            
            @unless(Auth::user()->hasRole(['agent', 'admin', 'gestionnaire']))
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

            @if(Auth::user()->role === 'gestionnaire')
            <a href="{{ route('materiels.create') }}" class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.03)] hover:border-ci-orange hover:shadow-md transition-all group flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-black text-gray-900 mb-1">Ajouter Matériel</h4>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nouveau terminal</span>
                </div>
                <div class="h-10 w-10 bg-orange-50 text-ci-orange rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-laptop-medical"></i>
                </div>
            </a>
            @endif
        </div>
    </div>
    @elseif(Auth::user()->role === 'admin')
    <!-- Grille de Contenu Spécifique pour l'Admin (Pas de graphiques, mais Actions Rapides mieux mises en page) -->
    <div class="mt-8">
        <h3 class="text-xl font-black text-gray-900 tracking-tight pl-2 mb-6">Actions Rapides</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('admin.users.create') }}" class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.03)] hover:border-ci-orange hover:shadow-md transition-all group flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-black text-gray-900 mb-1">Ajouter Profil</h4>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Gestion des accès</span>
                </div>
                <div class="h-10 w-10 bg-orange-50 text-ci-orange rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
            </a>

            <a href="{{ route('admin.organisation.index') }}" class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.03)] hover:border-ci-green hover:shadow-md transition-all group flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-black text-gray-900 mb-1">Ajouter Structure</h4>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Ville, Direction, Service</span>
                </div>
                <div class="h-10 w-10 bg-green-50 text-ci-green rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-folder-plus"></i>
                </div>
            </a>

            <a href="{{ route('materiels.index') }}" class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.03)] hover:border-ci-green-dark hover:shadow-md transition-all group flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-black text-gray-900 mb-1">Consulter Inventaire</h4>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Liste complète</span>
                </div>
                <div class="h-10 w-10 bg-green-900 text-white rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-desktop"></i>
                </div>
            </a>
        </div>
    </div>
    @endif
    <!-- Scripts pour les graphiques -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // =====================================================
            // CHARTS DR
            // =====================================================
            @if(Auth::user()->role === 'DR')

            // -- Chart 1 : Parc par Catégorie (Bar) --
            const categoryCanvas = document.getElementById('categoryChart');
            let categoryData = @json($stockParCategorie);

            // Données de démo si le stock est vide
            if (!categoryData || categoryData.length === 0) {
                categoryData = [
                    { categorie: 'Ordinateurs',  total: 42 },
                    { categorie: 'Imprimantes',  total: 18 },
                    { categorie: 'Scanners',     total: 9  },
                    { categorie: 'Serveurs',     total: 5  },
                    { categorie: 'Onduleurs',    total: 14 },
                ];
            }

            if (categoryCanvas) {
                new Chart(categoryCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: categoryData.map(item => item.categorie || item.label),
                        datasets: [{
                            label: 'Unités',
                            data: categoryData.map(item => item.total),
                            backgroundColor: ['#166534', '#ea7c2b', '#1e293b', '#10b981', '#6366f1'],
                            borderRadius: 10,
                            barThickness: 30
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#f8fafc' }, ticks: { font: { size: 9, weight: 'bold' }, color: '#94a3b8' } },
                            x: { grid: { display: false }, ticks: { font: { size: 9, weight: 'bold' }, color: '#94a3b8' } }
                        },
                        plugins: { legend: { display: false } }
                    }
                });
            }

            // -- Chart 2 : État des Directions (Doughnut) --
            const healthCanvas = document.getElementById('healthChart');
            const directionsData = @json($stats['directions_region']->map(fn($d) => $d->health));
            let healthy = 0, needsAction = 0, unhealthy = 0;
            directionsData.forEach(h => {
                if (h === 'healthy') healthy++;
                else if (h === 'needs_action') needsAction++;
                else unhealthy++;
            });

            // Démo si tout vaut 0
            if (healthy + needsAction + unhealthy === 0) {
                healthy = 2; needsAction = 2; unhealthy = 1;
            }

            if (healthCanvas) {
                new Chart(healthCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Opérationnelles', 'À traiter', 'Urgentes'],
                        datasets: [{
                            data: [healthy, needsAction, unhealthy],
                            backgroundColor: ['#166534', '#ea7c2b', '#ef4444'],
                            borderWidth: 4,
                            borderColor: '#ffffff',
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { padding: 16, usePointStyle: true, font: { size: 9, weight: 'bold' }, color: '#475569' }
                            }
                        }
                    }
                });
            }
            @endif

            // --- Charts pour rôles non-DR (Gestionnaire, DSI, SDGRTE, Chef CDI) ---
            @if(!in_array(Auth::user()->role, ['DR', 'agent', 'admin']))
            const repartitionData = @json($repartitionStatut);
            const statusCanvas = document.getElementById('statusChart');
            // Palette distincte par statut workflow
            const statusColorMap = {
                'pre_demande'            : '#3b82f6',
                'en_attente_dr'          : '#f59e0b',
                'transmise_gestionnaire' : '#ea7c2b',
                'proposition_sdgrte'     : '#8b5cf6',
                'en_attente_dsi'         : '#6366f1',
                'validee'                : '#166534',
                'rejetee'                : '#ef4444',
                'annulee'                : '#94a3b8',
            };
            if (statusCanvas && repartitionData.length > 0) {
                new Chart(statusCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: repartitionData.map(item => item.label),
                        datasets: [{
                            data: repartitionData.map(item => item.total),
                            backgroundColor: repartitionData.map(item => statusColorMap[item.statut] ?? '#94a3b8'),
                            borderWidth: 3,
                            borderColor: '#ffffff',
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { padding: 16, usePointStyle: true, font: { size: 9, weight: 'bold' }, color: '#475569' }
                            }
                        },
                        cutout: '72%'
                    }
                });
            }

            const catData2 = @json($stockParCategorie);
            const catCanvas2 = document.getElementById('categoryChart');
            if (catCanvas2 && catData2.length > 0) {
                new Chart(catCanvas2.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: catData2.map(item => item.categorie || item.label),
                        datasets: [{
                            label: 'Unités',
                            data: catData2.map(item => item.total),
                            backgroundColor: catData2.map((_, i) => {
                                const pal = ['#166534', '#ea7c2b', '#1e293b', '#10b981', '#6366f1', '#f59e0b'];
                                return pal[i % pal.length];
                            }),
                            borderRadius: 8,
                            barThickness: 22
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#f8fafc' }, ticks: { font: { size: 9, weight: 'bold' }, color: '#94a3b8' } },
                            x: { grid: { display: false }, ticks: { font: { size: 9, weight: 'bold' }, color: '#94a3b8' } }
                        },
                        plugins: { legend: { display: false } }
                    }
                });
            }
            @endif

            // --- Graphiques Admin ---
            @if(Auth::user()->role === 'admin')
            const userRoleData = @json($userRoleData);
            const adminRoleCanvas = document.getElementById('adminRoleChart');
            if (adminRoleCanvas && userRoleData.length > 0) {
                new Chart(adminRoleCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: userRoleData.map(item => item.label),
                        datasets: [{
                            data: userRoleData.map(item => item.total),
                            backgroundColor: ['#166534', '#ea7c2b', '#1e293b', '#10b981', '#6366f1', '#f59e0b', '#ef4444'],
                            borderWidth: 3,
                            borderColor: '#ffffff',
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '72%',
                        plugins: {
                            legend: { position: 'bottom', labels: { usePointStyle: true, font: { size: 9, weight: 'bold' }, color: '#475569' } }
                        }
                    }
                });
            }

            const userStructureData = @json($userStructureData);
            const adminStructureCanvas = document.getElementById('adminStructureChart');
            if (adminStructureCanvas && userStructureData.length > 0) {
                new Chart(adminStructureCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: userStructureData.map(item => item.nom_direction || item.label),
                        datasets: [{
                            label: 'Effectif',
                            data: userStructureData.map(item => item.total),
                            backgroundColor: '#1e293b',
                            borderRadius: 8
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                            y: { grid: { display: false } }
                        },
                        plugins: { legend: { display: false } }
                    }
                });
            }
            @endif
        });
    </script>
</x-app-layout>
