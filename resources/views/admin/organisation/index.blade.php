<x-app-layout>
    <div x-data="{ 
        activeTab: 'villes', 
        search: '',
        addType: 'ville'
    }" class="space-y-8">
        
        <x-slot name="header">
            <div>
                <h2 class="font-black text-4xl text-gray-900 leading-tight tracking-tighter mb-1">
                    Administration <span class="text-ci-green">Structure</span>
                </h2>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Gestion des entités administratives • DGI</p>
            </div>
        </x-slot>

        <!-- Quick Stats Overview -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- ... (stats restent identiques) ... -->
        </div>

        <!-- Dashboard Navigation & Search + ACTION BUTTON -->
        <div class="bg-white p-4 rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.02)] border border-gray-100 flex flex-col xl:flex-row items-center justify-between gap-6">
            <div class="flex flex-col lg:flex-row items-center gap-6 w-full xl:w-auto">
                <nav class="flex flex-wrap gap-2 p-1.5 bg-gray-50 rounded-2xl border border-gray-100/50">
                    <button @click="activeTab = 'villes'" :class="activeTab === 'villes' ? 'bg-white shadow-sm text-ci-green' : 'text-gray-400 hover:text-gray-600'" 
                        class="px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] transition-all flex items-center">
                        <i class="fa-solid fa-location-dot mr-2"></i> Villes
                    </button>
                    <!-- ... autres boutons de nav ... -->
                    <button @click="activeTab = 'directions'" :class="activeTab === 'directions' ? 'bg-white shadow-sm text-ci-green' : 'text-gray-400 hover:text-gray-600'" 
                        class="px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] transition-all flex items-center">
                        <i class="fa-solid fa-building mr-2"></i> Directions
                    </button>
                    <button @click="activeTab = 'services'" :class="activeTab === 'services' ? 'bg-white shadow-sm text-ci-orange' : 'text-gray-400 hover:text-gray-600'" 
                        class="px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] transition-all flex items-center">
                        <i class="fa-solid fa-briefcase mr-2"></i> Services
                    </button>
                    <button @click="activeTab = 'bureaux'" :class="activeTab === 'bureaux' ? 'bg-white shadow-sm text-slate-800' : 'text-gray-400 hover:text-gray-600'" 
                        class="px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] transition-all flex items-center">
                        <i class="fa-solid fa-door-open mr-2"></i> Bureaux
                    </button>
                </nav>

                <!-- NEW BUTTON POSITION: Right next to tabs to be sure it's visible -->
                <div x-show="activeTab !== 'add'" x-transition>
                    <button @click="addType = activeTab.slice(0, -1); activeTab = 'add'" 
                        class="inline-flex items-center px-6 py-3.5 bg-ci-green text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-xl shadow-xl shadow-ci-green/10 hover:scale-105 transition-all active:scale-95 whitespace-nowrap">
                        <i class="fa-solid fa-plus-circle text-white mr-3"></i> 
                        Ajouter <span x-text="activeTab === 'villes' ? 'une Ville' : (activeTab === 'directions' ? 'une Direction' : (activeTab === 'services' ? 'un Service' : 'un Bureau'))"></span>
                    </button>
                </div>
            </div>

            <div class="relative w-full lg:w-96">
                <!-- ... search input ... -->
                <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-gray-300">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" x-model="search" 
                       class="block w-full pl-12 pr-6 py-4 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-[1.5rem] transition-all outline-none text-sm font-bold shadow-sm placeholder:text-gray-300"
                       placeholder="Rechercher...">
            </div>
        </div>

        <!-- Main Content (Table Style) -->
        <div class="relative min-h-[400px]">

            <!-- TAB: VILLES -->
            <div x-show="activeTab === 'villes'" x-transition class="bg-white rounded-[2.5rem] border border-gray-100 overflow-hidden shadow-[0_10px_40px_rgba(0,0,0,0.01)]">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 italic">Désignation</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">Charge Administrative</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($villes as $ville)
                        <tr x-show="search === '' || '{{ strtolower($ville->nom_ville) }}'.includes(search.toLowerCase())" class="hover:bg-gray-50/80 transition-all group">
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 bg-ci-green/5 rounded-xl flex items-center justify-center text-ci-green mr-4 border border-ci-green/10">
                                        <i class="fa-solid fa-city text-xs"></i>
                                    </div>
                                    <span class="text-sm font-black text-gray-900 uppercase tracking-tighter">{{ $ville->nom_ville }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-4 py-1.5 bg-gray-50 text-gray-500 rounded-full text-[10px] font-black uppercase tracking-widest border border-gray-100">
                                    {{ $ville->directions->count() }} Directions actives
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <form action="{{ route('admin.organisation.ville.destroy', $ville) }}" method="POST" onsubmit="return confirm('Attention: La suppression supprimera toutes les entités rattachées. Confirmer ?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-gray-300 hover:text-red-500 transition-all hover:scale-125"><i class="fa-solid fa-trash-can"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-8 py-20 text-center text-gray-400 font-bold uppercase text-[10px] tracking-widest italic">Aucune localité enregistrée</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- TAB: DIRECTIONS -->
            <div x-show="activeTab === 'directions'" x-transition class="bg-white rounded-[2.5rem] border border-gray-100 overflow-hidden shadow-[0_10px_40px_rgba(0,0,0,0.01)]">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 italic">Direction Générale / Régionale</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">Ville de Rattachement</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 text-center">Services</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($villes as $ville)
                            @foreach($ville->directions as $dir)
                            <tr x-show="search === '' || '{{ strtolower($dir->nom_direction) }}'.includes(search.toLowerCase())" class="hover:bg-gray-50/80 transition-all group">
                                <td class="px-8 py-6">
                                    <span class="text-sm font-black text-slate-900 border-l-4 border-ci-orange pl-4 uppercase tracking-tighter">{{ $dir->nom_direction }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest italic">{{ $ville->nom_ville }}</span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span class="px-3 py-1 bg-ci-orange/5 text-ci-orange border border-ci-orange/10 rounded-lg text-[10px] font-black">{{ $dir->services->count() }}</span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <form action="{{ route('admin.organisation.direction.destroy', $dir) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="p-2 text-gray-300 hover:text-red-500 transition-all hover:scale-125"><i class="fa-solid fa-trash-can"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- TAB: SERVICES -->
            <div x-show="activeTab === 'services'" x-transition class="bg-white rounded-[2.5rem] border border-gray-100 overflow-hidden shadow-[0_10px_40px_rgba(0,0,0,0.01)]">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 italic">Unité de Service</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">Appartenance Hiérarchique</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 text-center">Bureaux</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($villes as $ville)
                            @foreach($ville->directions as $dir)
                                @foreach($dir->services as $srv)
                                <tr x-show="search === '' || '{{ strtolower($srv->nom_service) }}'.includes(search.toLowerCase())" class="hover:bg-gray-50/80 transition-all group">
                                    <td class="px-8 py-6 font-black text-slate-800 text-sm uppercase tracking-tighter">{{ $srv->nom_service }}</td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-col">
                                            <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">{{ $dir->nom_direction }}</span>
                                            <span class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">{{ $ville->nom_ville }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-black">{{ $srv->bureaux->count() }}</span>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <form action="{{ route('admin.organisation.service.destroy', $srv) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button class="p-2 text-gray-300 hover:text-red-500 transition-all hover:scale-125"><i class="fa-solid fa-trash-can"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- TAB: BUREAUX -->
            <div x-show="activeTab === 'bureaux'" x-transition class="bg-white rounded-[2.5rem] border border-gray-100 overflow-hidden shadow-[0_10px_40px_rgba(0,0,0,0.01)]">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 italic">Désignation Bureau</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">Service Rataché</th>
                            <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($villes as $ville)
                            @foreach($ville->directions as $dir)
                                @foreach($dir->services as $srv)
                                    @foreach($srv->bureaux as $bur)
                                    <tr x-show="search === '' || '{{ strtolower($bur->nom_bureau) }}'.includes(search.toLowerCase())" class="hover:bg-gray-50/80 transition-all group">
                                        <td class="px-8 py-6 italic font-black text-slate-900 text-xs uppercase tracking-tighter">{{ $bur->nom_bureau }}</td>
                                        <td class="px-8 py-6">
                                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $srv->nom_service }} — {{ $dir->nom_direction }}</span>
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                            <form action="{{ route('admin.organisation.bureau.destroy', $bur) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button class="p-2 text-gray-300 hover:text-red-500 transition-all hover:scale-125"><i class="fa-solid fa-trash-can"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- TAB: AJOUT (Standard App Style) -->
            <div x-show="activeTab === 'add'" x-cloak x-transition class="max-w-4xl mx-auto space-y-8 py-10">
                <div class="bg-white rounded-[3rem] p-12 border border-gray-100 shadow-[0_20px_80px_rgba(0,0,0,0.05)] relative overflow-hidden">
                    
                    <div class="absolute top-0 right-0 w-64 h-64 bg-gray-50/50 rounded-full -translate-y-32 translate-x-32 pointer-events-none"></div>

                    <div class="flex items-center justify-between mb-12 relative z-10">
                        <div>
                            <h3 class="text-3xl font-black text-slate-900 uppercase tracking-tighter">
                                Enregistrement <span class="text-ci-green" x-text="addType === 'ville' ? 'Ville' : (addType === 'direction' ? 'Direction' : (addType === 'service' ? 'Service' : 'Bureau'))"></span>
                            </h3>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-2 px-1 border-l-3 border-ci-orange ml-1">Formulaire de configuration administrative</p>
                        </div>
                        <button @click="activeTab = addType + 's'" class="h-14 w-14 bg-gray-50 flex items-center justify-center rounded-[1.25rem] text-gray-400 hover:text-slate-900 transition-all border border-gray-100 shadow-sm"><i class="fa-solid fa-arrow-left"></i></button>
                    </div>

                    <!-- Ville form -->
                    <div x-show="addType === 'ville'" x-cloak class="relative z-10">
                        <form action="{{ route('admin.organisation.ville.store') }}" method="POST" class="space-y-10">
                            @csrf
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Alias ou Nom de la Localité</label>
                                <input type="text" name="nom_ville" required class="w-full bg-gray-50/50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-2xl text-sm p-6 font-bold transition-all outline-none shadow-sm placeholder:text-gray-300" placeholder="ex: ABIDJAN SUD">
                            </div>
                            <button type="submit" class="w-full py-6 bg-slate-900 text-white rounded-[2rem] font-black text-xs uppercase tracking-[0.3em] shadow-2xl shadow-slate-900/10 hover:scale-105 active:scale-95 transition-all">
                                <i class="fa-solid fa-check-double mr-3 text-ci-green"></i> Sauvegarder la Localité
                            </button>
                        </form>
                    </div>

                    <!-- Direction form -->
                    <div x-show="addType === 'direction'" x-cloak class="relative z-10">
                        <form action="{{ route('admin.organisation.direction.store') }}" method="POST" class="space-y-10">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                <div class="space-y-4">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Ville de rattachement</label>
                                    <div class="relative">
                                        <select name="id_ville" required class="w-full bg-gray-50/50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-2xl text-sm p-6 font-bold transition-all outline-none appearance-none cursor-pointer">
                                            @foreach($villes as $ville)
                                                <option value="{{ $ville->id }}">{{ $ville->nom_ville }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-gray-300"><i class="fa-solid fa-chevron-down"></i></div>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Intitulé de la Direction</label>
                                    <input type="text" name="nom_direction" required class="w-full bg-gray-50/50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-2xl text-sm p-6 font-bold transition-all outline-none shadow-sm" placeholder="ex: DR Plateau I">
                                </div>
                            </div>
                            <button type="submit" class="w-full py-6 bg-ci-green text-white rounded-[2rem] font-black text-xs uppercase tracking-[0.3em] shadow-2xl shadow-ci-green/10 hover:scale-105 active:scale-95 transition-all">Créer la direction</button>
                        </form>
                    </div>

                    <!-- Service form -->
                    <div x-show="addType === 'service'" x-cloak class="relative z-10">
                        <form action="{{ route('admin.organisation.service.store') }}" method="POST" class="space-y-10">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                <div class="space-y-4">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Direction Parente</label>
                                    <div class="relative">
                                        <select name="id_direction" required class="w-full bg-gray-50/50 border-2 border-transparent focus:border-ci-orange focus:bg-white rounded-2xl text-sm p-6 font-bold transition-all outline-none appearance-none cursor-pointer">
                                            @foreach($villes as $ville)
                                                @foreach($ville->directions as $dir)
                                                    <option value="{{ $dir->id }}">{{ $dir->nom_direction }} ({{ $ville->nom_ville }})</option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                        <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-gray-300"><i class="fa-solid fa-chevron-down"></i></div>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Intitulé du Service</label>
                                    <input type="text" name="nom_service" required class="w-full bg-gray-50/50 border-2 border-transparent focus:border-ci-orange focus:bg-white rounded-2xl text-sm p-6 font-bold transition-all outline-none" placeholder="ex: Ressouces Humaines">
                                </div>
                            </div>
                            <button type="submit" class="w-full py-6 bg-ci-orange text-white rounded-[2rem] font-black text-xs uppercase tracking-[0.3em] shadow-2xl shadow-ci-orange/10 hover:scale-105 active:scale-95 transition-all">Valider le Service</button>
                        </form>
                    </div>

                    <!-- Bureau form -->
                    <div x-show="addType === 'bureau'" x-cloak class="relative z-10">
                        <form action="{{ route('admin.organisation.bureau.store') }}" method="POST" class="space-y-10">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                <div class="space-y-4">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Service Affilié</label>
                                    <div class="relative">
                                        <select name="id_service" required class="w-full bg-gray-50/50 border-2 border-transparent focus:border-slate-900 focus:bg-white rounded-2xl text-sm p-6 font-bold transition-all outline-none appearance-none cursor-pointer">
                                            @foreach($villes as $ville)
                                                @foreach($ville->directions as $dir)
                                                    @foreach($dir->services as $srv)
                                                        <option value="{{ $srv->id }}">{{ $srv->nom_service }} ({{ $dir->nom_direction }})</option>
                                                    @endforeach
                                                @endforeach
                                            @endforeach
                                        </select>
                                        <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-gray-300"><i class="fa-solid fa-chevron-down"></i></div>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">Numéro / Nom du Bureau</label>
                                    <input type="text" name="nom_bureau" required class="w-full bg-gray-50/50 border-2 border-transparent focus:border-slate-900 focus:bg-white rounded-2xl text-sm p-6 font-bold transition-all outline-none" placeholder="ex: Bureau 304">
                                </div>
                            </div>
                            <button type="submit" class="w-full py-6 bg-slate-900 text-white rounded-[2rem] font-black text-xs uppercase tracking-[0.3em] shadow-2xl shadow-slate-900/10 hover:scale-105 active:scale-95 transition-all">Confirmer la Création</button>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
