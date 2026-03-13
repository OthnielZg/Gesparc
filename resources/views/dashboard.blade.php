<x-app-layout>
    <!-- En-tête de bienvenue correspondant à la maquette -->
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Bonjour {{ Auth::user()->prenom }} !</h1>
        <p class="text-gray-500 font-bold mt-1">Voici le résumé de votre activité sur le parc informatique.</p>
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
                <!-- Premium Card: Services Direction -->
                <div class="bg-white p-6 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 hover:-translate-y-1 transition-transform duration-300">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center space-x-2 mb-4">
                                <div class="bg-ci-green-dark text-white p-2 rounded-xl shadow-md shadow-green-900/20">
                                    <i class="fa-solid fa-briefcase"></i>
                                </div>
                                <span class="text-sm font-black text-gray-500 tracking-wide uppercase">Services</span>
                            </div>
                            <h3 class="text-4xl font-black text-gray-900">{{ $stats['total_services'] ?? 0 }}</h3>
                            <p class="text-[10px] font-bold text-ci-green-dark uppercase tracking-widest mt-1">Entités rattachées</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Carte Premium 3 : À Valider -->
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

    @if(!in_array(Auth::user()->role, ['agent', 'admin']))
    <!-- Grille de Contenu Principal pour Managers/Validants -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Real Charts Grid (Col span 2) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="grid grid-cols-1 {{ Auth::user()->role === 'gestionnaire' ? '' : 'md:grid-cols-2' }} gap-6">
                @if(Auth::user()->role !== 'gestionnaire')
                <!-- Graphique 1 : Répartition des Demandes (Doughnut) -->
                <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 p-6 flex flex-col h-full min-h-[320px]">
                    <h3 class="text-sm font-black text-gray-900 tracking-tight mb-4 uppercase">
                        @if(Auth::user()->role === 'agent') Mes Demandes
                        @elseif(Auth::user()->role === 'chef_cdi') Demandes du Service
                        @elseif(Auth::user()->role === 'DR') Demandes Direction
                        @elseif(Auth::user()->role === 'admin') Répartition par Rôle
                        @else État du Workflow @endif
                    </h3>
                    <div class="flex-1 relative">
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
                <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 p-6 flex flex-col h-full min-h-[320px]">
                    <h3 class="text-sm font-black text-gray-900 tracking-tight mb-4 uppercase">
                        @if(Auth::user()->role === 'agent') Mes Matériels
                        @elseif(Auth::user()->role === 'chef_cdi') Stock du Service
                        @elseif(Auth::user()->role === 'DR') Stock Direction
                        @elseif(Auth::user()->role === 'admin') Structure Organisationnelle
                        @else Stock par Catégorie @endif
                    </h3>
                    <div class="flex-1 relative">
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

            <!-- Scripts pour les graphiques -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // --- Graphiques Standards ---
                    const repartitionData = @json($repartitionStatut);
                    const statusCanvas = document.getElementById('statusChart');
                    if (statusCanvas && repartitionData.length > 0) {
                        const ctxStatus = statusCanvas.getContext('2d');
                        new Chart(ctxStatus, {
                            type: 'doughnut',
                            data: {
                                labels: repartitionData.map(item => item.label),
                                datasets: [{
                                    data: repartitionData.map(item => item.total),
                                    backgroundColor: repartitionData.map(item => item.color),
                                    borderWidth: 0,
                                    hoverOffset: 10
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: { padding: 20, usePointStyle: true, font: { size: 10, weight: 'bold' } }
                                    }
                                },
                                cutout: '75%'
                            }
                        });
                    }

                    const categoryData = @json($stockParCategorie);
                    const categoryCanvas = document.getElementById('categoryChart');
                    if (categoryCanvas && categoryData.length > 0) {
                        const ctxCategory = categoryCanvas.getContext('2d');
                        new Chart(ctxCategory, {
                            type: 'bar',
                            data: {
                                labels: categoryData.map(item => item.categorie),
                                datasets: [{
                                    label: 'Unités',
                                    data: categoryData.map(item => item.total),
                                    backgroundColor: '#166534',
                                    borderRadius: 12,
                                    barThickness: 20
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { size: 9, weight: 'bold' } } },
                                    x: { grid: { display: false }, ticks: { font: { size: 9, weight: 'bold' } } }
                                },
                                plugins: { legend: { display: false } }
                            }
                        });
                    }

                    // --- Graphiques Admin ---
                    @if(Auth::user()->role === 'admin')
                    const userRoleData = @json($userRoleData);
                    const adminRoleCanvas = document.getElementById('adminRoleChart');
                    if (adminRoleCanvas && userRoleData.length > 0) {
                        new Chart(adminRoleCanvas.getContext('2d'), {
                            type: 'polarArea',
                            data: {
                                labels: userRoleData.map(item => item.label),
                                datasets: [{
                                    data: userRoleData.map(item => item.total),
                                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#6366f1', '#8b5cf6', '#ec4899', '#ef4444'],
                                    borderWidth: 0
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { position: 'bottom', labels: { usePointStyle: true, font: { size: 9, weight: 'bold' } } }
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
</x-app-layout>
