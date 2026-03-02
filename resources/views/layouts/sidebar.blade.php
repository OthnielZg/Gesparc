<div class="flex flex-col h-full overflow-y-auto text-white">
    <!-- Sidebar spacing since logo is in topbar -->
    <div class="h-6"></div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-8 space-y-2">
        <!-- Section: Personnel -->
        <div class="pb-2">
            <p class="px-4 text-[10px] font-black text-white/40 uppercase tracking-[0.2em]">Personnel</p>
        </div>
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="fa-chart-pie">
            Tableau de bord
        </x-nav-link>

        @if(!Auth::user()->hasRole(['admin', 'gestionnaire']))
            <x-nav-link :href="route('demandes.index')" :active="request()->routeIs('demandes.index')"
                icon="fa-file-invoice">
                {{ Auth::user()->hasRole('agent') ? 'Mes Demandes' : 'Suivi des Dossiers' }}
            </x-nav-link>
        @endif
        @if(Auth::user()->hasRole(['agent', 'chef_cdi', 'DR']))
            <x-nav-link :href="route('mon-materiel.index')" :active="request()->routeIs('mon-materiel.*')" icon="fa-laptop">
                @php
                    echo match (Auth::user()->role) {
                        'agent' => 'Mon Matériel',
                        'chef_cdi' => 'Matériels de mon Service',
                        'DR' => 'Parc de ma Direction',
                        default => 'Mon Matériel',
                    };
                @endphp
            </x-nav-link>
        @endif

        <!-- Section: Validations (Contextual based on role) -->
        @if(Auth::user()->hasRole(['chef_cdi', 'DR', 'gestionnaire', 'SDREVT', 'DSI']))
            <div class="pt-6 pb-2">
                <p class="px-4 text-[10px] font-black text-white/40 uppercase tracking-[0.2em]">Validations</p>
            </div>

            @if(Auth::user()->hasRole('chef_cdi'))
                <x-nav-link :href="route('demandes.index', ['statut' => 'pre_demande'])"
                    :active="request()->fullUrlIs(route('demandes.index', ['statut' => 'pre_demande']))"
                    icon="fa-file-circle-check">
                    Officialisation
                </x-nav-link>
            @endif

            @if(Auth::user()->hasRole(['DR', 'gestionnaire', 'SDREVT', 'DSI']))
                <x-nav-link :href="route('validations.index')" :active="request()->routeIs('validations.*')"
                    icon="fa-check-double">
                    @php
                        $label = match (Auth::user()->role) {
                            'DR' => 'Validation DR',
                            'gestionnaire' => 'Dotation Matériel',
                            'SDREVT' => 'Validation SDREVT',
                            'DSI' => 'Validation Finale DSI',
                            default => 'Mes Validations'
                        };
                    @endphp
                    {{ $label }}
                </x-nav-link>
            @endif
        @endif

        <!-- Section: Inventaire (Gestionnaires & Admin) -->
        @if(Auth::user()->hasRole(['admin', 'gestionnaire']))
            <div class="pt-6 pb-2">
                <p class="px-4 text-[10px] font-black text-white/40 uppercase tracking-[0.2em]">Inventaire</p>
            </div>
            <x-nav-link :href="route('materiels.index')" :active="request()->routeIs('materiels.*')" icon="fa-laptop">
                Matériels
            </x-nav-link>
            <x-nav-link :href="route('affectations.index')" :active="request()->routeIs('affectations.*')"
                icon="fa-hand-holding-hand">
                Affectations
            </x-nav-link>
        @endif

        <!-- Section: Administration (Admin only) -->
        @if(Auth::user()->hasRole(['admin']))
            <div class="pt-6 pb-2">
                <p class="px-4 text-[10px] font-black text-white/40 uppercase tracking-[0.2em]">Administration</p>
            </div>
            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" icon="fa-users">
                Utilisateurs
            </x-nav-link>
            <x-nav-link :href="route('admin.organisation.index')" :active="request()->routeIs('admin.organisation.*')"
                icon="fa-sitemap">
                Organisationnel
            </x-nav-link>
            <x-nav-link :href="route('admin.fournisseurs.index')" :active="request()->routeIs('admin.fournisseurs.*')"
                icon="fa-truck-field">
                Fournisseurs
            </x-nav-link>
        @endif
    </nav>

    <!-- Footer Logout Area -->
    <div class="p-6 mt-auto">
        <button @click="showLogoutModal = true"
            class="w-full flex items-center px-4 py-3 text-sm font-bold text-white/60 hover:text-white hover:bg-white/10 rounded-2xl transition-all group">
            <i
                class="fa-solid fa-arrow-right-from-bracket mr-4 text-white/50 group-hover:text-ci-orange transition-colors"></i>
            Déconnexion
        </button>
    </div>
</div>