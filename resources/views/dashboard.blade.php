<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-3xl text-gray-900 leading-tight tracking-tighter">
                Tableau de <span class="text-ci-orange">Bord</span>
            </h2>
            <div class="flex items-center bg-white px-4 py-2 rounded-2xl shadow-sm border border-gray-100">
                <div class="h-2 w-2 rounded-full bg-ci-green animate-pulse mr-2"></div>
                <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Système Opérationnel</span>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stat Card: Total Materiels -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-laptop text-xl"></i>
                </div>
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Parc</span>
            </div>
            <p class="text-3xl font-black text-gray-900">{{ $stats['total_materiels'] }}</p>
            <p class="text-xs font-bold text-gray-500 mt-1 uppercase tracking-tighter">Unités enregistrées</p>
        </div>

        <!-- Stat Card: Disponible -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-green-50 text-ci-green rounded-2xl group-hover:bg-ci-green group-hover:text-white transition-colors">
                    <i class="fa-solid fa-check-circle text-xl"></i>
                </div>
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Stock Disponible</span>
            </div>
            <p class="text-3xl font-black text-gray-900">{{ $stats['disponible'] }}</p>
            <p class="text-xs font-bold text-gray-500 mt-1 uppercase tracking-tighter">Prêts pour affectation</p>
        </div>

        <!-- Stat Card: Pending Validations or Users -->
        @if(isset($stats['demandes_a_valider']))
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-orange-50 text-ci-orange rounded-2xl group-hover:bg-ci-orange group-hover:text-white transition-colors">
                    <i class="fa-solid fa-clock-rotate-left text-xl"></i>
                </div>
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">À Valider</span>
            </div>
            <p class="text-3xl font-black text-gray-900">{{ $stats['demandes_a_valider'] }}</p>
            <p class="text-xs font-bold text-gray-500 mt-1 uppercase tracking-tighter">Actions requises</p>
        </div>
        @elseif(isset($stats['total_users']))
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-slate-100 text-slate-600 rounded-2xl group-hover:bg-slate-900 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-users text-xl"></i>
                </div>
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Utilisateurs</span>
            </div>
            <p class="text-3xl font-black text-gray-900">{{ $stats['total_users'] }}</p>
            <p class="text-xs font-bold text-gray-500 mt-1 uppercase tracking-tighter">Comptes actifs</p>
        </div>
        @endif

        <!-- Stat Card: Mes Demandes -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-2xl group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-file-invoice text-xl"></i>
                </div>
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Mes Activités</span>
            </div>
            <p class="text-3xl font-black text-gray-900">{{ $stats['mes_demandes'] }}</p>
            <p class="text-xs font-bold text-gray-500 mt-1 uppercase tracking-tighter">Demandes personnelles</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8">
        <h3 class="text-lg font-black text-gray-900 uppercase tracking-widest mb-6">Actions Rapides</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('demandes.create') }}" class="flex items-center p-4 bg-ci-green text-white rounded-2xl hover:scale-[1.02] transition-transform shadow-lg shadow-green-900/10">
                <div class="h-12 w-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                    <i class="fa-solid fa-plus text-xl"></i>
                </div>
                <div>
                    <p class="font-black text-sm uppercase tracking-tighter">Nouvelle Demande</p>
                    <p class="text-[10px] opacity-80 uppercase font-bold tracking-widest">Signaler un besoin</p>
                </div>
            </a>
            
            @if(Auth::user()->hasRole(['admin']))
            <a href="{{ route('admin.users.create') }}" class="flex items-center p-4 bg-slate-900 text-white rounded-2xl hover:scale-[1.02] transition-transform shadow-lg shadow-slate-900/10">
                <div class="h-12 w-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                    <i class="fa-solid fa-user-plus text-xl"></i>
                </div>
                <div>
                    <p class="font-black text-sm uppercase tracking-tighter">Ajouter Utilisateur</p>
                    <p class="text-[10px] opacity-80 uppercase font-bold tracking-widest">Gestion des accès</p>
                </div>
            </a>
            @endif

            <a href="{{ route('materiels.index') }}" class="flex items-center p-4 bg-white text-gray-900 border border-gray-100 rounded-2xl hover:scale-[1.02] transition-transform shadow-sm">
                <div class="h-12 w-12 bg-gray-50 text-ci-orange rounded-xl flex items-center justify-center mr-4">
                    <i class="fa-solid fa-magnifying-glass text-xl"></i>
                </div>
                <div>
                    <p class="font-black text-sm uppercase tracking-tighter">Consulter l'Inventaire</p>
                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Liste des matériels</p>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>
