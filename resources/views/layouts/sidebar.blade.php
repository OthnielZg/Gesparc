<div class="flex flex-col h-full overflow-y-auto">
    <!-- Logo -->
    <div class="flex items-center h-20 flex-shrink-0 px-6 bg-slate-950/50">
        <img class="h-10 w-auto" src="{{ asset('img/logo_dgi.png') }}" alt="DGI">
        <span class="ml-3 text-white font-black text-xl tracking-tighter">GES<span class="text-ci-orange">PARC</span></span>
    </div>

    <!-- User Mini Profile -->
    <div class="px-6 py-4 border-b border-slate-800">
        <div class="flex items-center">
            <div class="h-10 w-10 rounded-xl bg-ci-green flex items-center justify-center text-white font-bold text-lg shadow-lg">
                {{ substr(Auth::user()->prenom, 0, 1) }}{{ substr(Auth::user()->nom, 0, 1) }}
            </div>
            <div class="ml-3">
                <p class="text-sm font-bold text-white truncate">{{ Auth::user()->full_name }}</p>
                <p class="text-[10px] font-black text-ci-orange uppercase tracking-widest">{{ Auth::user()->getRoleLabel() }}</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-1">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="fa-chart-pie">
            Tableau de Bord
        </x-nav-link>

        @if(Auth::user()->hasRole(['admin']))
            <div class="pt-4 pb-2">
                <p class="px-3 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Administration</p>
            </div>
            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" icon="fa-users">
                Utilisateurs
            </x-nav-link>
            <x-nav-link :href="route('admin.organisation.index')" :active="request()->routeIs('admin.organisation.*')" icon="fa-sitemap">
                Organisationnel
            </x-nav-link>
            <x-nav-link :href="route('admin.fournisseurs.index')" :active="request()->routeIs('admin.fournisseurs.*')" icon="fa-truck-field">
                Fournisseurs
            </x-nav-link>
        @endif

        <div class="pt-4 pb-2">
            <p class="px-3 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Gestion du Parc</p>
        </div>
        <x-nav-link :href="route('materiels.index')" :active="request()->routeIs('materiels.*')" icon="fa-laptop">
            Matériels
        </x-nav-link>

        <div class="pt-4 pb-2">
            <p class="px-3 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Demandes & Flux</p>
        </div>
        <x-nav-link :href="route('demandes.index')" :active="request()->routeIs('demandes.*')" icon="fa-file-invoice">
            Demandes
        </x-nav-link>
        
        @if(Auth::user()->hasRole(['DR', 'gestionnaire', 'SDREVT', 'DSI']))
            <x-nav-link :href="route('validations.index')" :active="request()->routeIs('validations.*')" icon="fa-check-double">
                Validations En Attente
            </x-nav-link>
        @endif

        <x-nav-link :href="route('affectations.index')" :active="request()->routeIs('affectations.*')" icon="fa-hand-holding-hand">
            Affectations
        </x-nav-link>
    </nav>

    <!-- Footer Logout -->
    <div class="p-4 bg-slate-950/50">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center px-4 py-3 text-sm font-bold text-slate-400 hover:text-white hover:bg-red-500/10 hover:text-red-500 rounded-xl transition-all group">
                <i class="fa-solid fa-power-off mr-3 text-red-500 transition-transform group-hover:scale-110"></i>
                Déconnexion
            </button>
        </form>
    </div>
</div>
