<div class="flex flex-col h-full overflow-y-auto text-white">
    <!-- Sidebar spacing since logo is in topbar -->
    <div class="h-6"></div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-8 space-y-2">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="fa-chart-pie">
            Tableau de bord
        </x-nav-link>

        @if(Auth::user()->hasRole(['admin']))
            <div class="pt-6 pb-2">
                <p class="px-4 text-[10px] font-black text-white/40 uppercase tracking-[0.2em]">Administration</p>
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

        <div class="pt-6 pb-2">
            <p class="px-4 text-[10px] font-black text-white/40 uppercase tracking-[0.2em]">Gestion</p>
        </div>
        <x-nav-link :href="route('materiels.index')" :active="request()->routeIs('materiels.*')" icon="fa-laptop">
            Matériels
        </x-nav-link>

        <div class="pt-6 pb-2">
            <p class="px-4 text-[10px] font-black text-white/40 uppercase tracking-[0.2em]">Flux</p>
        </div>
        <x-nav-link :href="route('demandes.index')" :active="request()->routeIs('demandes.*')" icon="fa-file-invoice">
            Demandes
        </x-nav-link>
        
        @if(Auth::user()->hasRole(['DR', 'gestionnaire', 'SDREVT', 'DSI']))
            <x-nav-link :href="route('validations.index')" :active="request()->routeIs('validations.*')" icon="fa-check-double">
                Validations
            </x-nav-link>
        @endif

        <x-nav-link :href="route('affectations.index')" :active="request()->routeIs('affectations.*')" icon="fa-hand-holding-hand">
            Affectations
        </x-nav-link>
    </nav>

    <!-- Footer Logout Area -->
    <div class="p-6 mt-auto">
        <button @click="showLogoutModal = true" class="w-full flex items-center px-4 py-3 text-sm font-bold text-white/60 hover:text-white hover:bg-white/10 rounded-2xl transition-all group">
            <i class="fa-solid fa-arrow-right-from-bracket mr-4 text-white/50 group-hover:text-ci-orange transition-colors"></i>
            Déconnexion
        </button>
    </div>
</div>
