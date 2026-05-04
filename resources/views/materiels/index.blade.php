<x-app-layout>
    <!-- En-tête cohérent -->
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight uppercase leading-none">
                Inventaire du <span class="text-ci-green">Matériel</span>
            </h1>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mt-2">Base de données du patrimoine IT · DGI</p>
        </div>
        @if(auth()->user()->role === 'gestionnaire')
        <a href="{{ route('materiels.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-ci-green text-white font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-opacity-90 transition-all">
            <i class="fa-solid fa-plus"></i> Ajouter un Matériel
        </a>
        @endif
    </div>

    <!-- KPIs style Radar – 4 indicateurs -->
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden mb-8">
        <div class="px-8 py-4 bg-slate-50/60 border-b border-gray-100">
            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Vue d'ensemble du Stock</span>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-y md:divide-y-0 divide-gray-50">
            @php
                $kpis = [
                    ['label' => 'Total Stock',  'val' => \App\Models\Materiel::count(),                                'icon' => 'fa-cubes',              'color' => 'text-slate-700'],
                    ['label' => 'Disponibles',  'val' => \App\Models\Materiel::where('statut', 'disponible')->count(), 'icon' => 'fa-box-open',           'color' => 'text-ci-green'],
                    ['label' => 'Affectés',     'val' => \App\Models\Materiel::where('statut', 'affecté')->count(),    'icon' => 'fa-user-tag',           'color' => 'text-ci-orange'],
                    ['label' => 'En Panne',     'val' => \App\Models\Materiel::where('statut', 'en_panne')->count(),   'icon' => 'fa-screwdriver-wrench', 'color' => 'text-red-500'],
                ];
            @endphp
            @foreach($kpis as $kpi)
            <div class="px-8 py-6 flex items-center gap-4">
                <i class="fa-solid {{ $kpi['icon'] }} {{ $kpi['color'] }} text-lg w-5"></i>
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $kpi['label'] }}</p>
                    <p class="text-2xl font-black {{ $kpi['color'] }} leading-none mt-0.5">{{ $kpi['val'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Filtres style Radar -->
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden mb-8">
        <div class="px-8 py-4 bg-slate-50/60 border-b border-gray-100">
            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Recherche & Filtres</span>
        </div>
        <div class="px-8 py-6">
            <form action="{{ route('materiels.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="block w-full px-5 py-3 bg-slate-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold placeholder:text-gray-300"
                           placeholder="N° de série, Marque, Modèle...">
                </div>
                <div class="w-full md:w-56">
                    <select name="statut" class="block w-full px-5 py-3 bg-slate-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold cursor-pointer">
                        <option value="">Tous les états</option>
                        @foreach(\App\Models\Materiel::statutLabels() as $key => $label)
                            <option value="{{ $key }}" {{ request('statut') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-7 py-3 bg-slate-900 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-black transition-all">
                    Filtrer
                </button>
                @if(request()->hasAny(['search', 'statut']))
                <a href="{{ route('materiels.index') }}" class="text-gray-400 hover:text-red-500 font-black text-[10px] uppercase tracking-widest transition-colors whitespace-nowrap">
                    <i class="fa-solid fa-rotate-left mr-1"></i> Reset
                </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Tableau style Radar -->
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-8 py-4 bg-slate-50/60 border-b border-gray-100 flex items-center justify-between">
            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Registre du Patrimoine</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[9px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50">
                        <th class="px-8 py-4">Désignation</th>
                        <th class="px-8 py-4">N° Série</th>
                        <th class="px-8 py-4">Catégorie</th>
                        <th class="px-8 py-4">État</th>
                        <th class="px-8 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($materiels as $materiel)
                        <tr class="hover:bg-slate-50/50 transition-colors cursor-pointer group"
                            onclick="window.location='{{ route('materiels.show', $materiel) }}'">
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-3">
                                    @php
                                        $icons = [
                                            'ordinateur' => 'fa-laptop-code',
                                            'imprimante' => 'fa-print',
                                            'scanner'    => 'fa-barcode',
                                            'serveur'    => 'fa-server',
                                            'onduleur'   => 'fa-battery-full',
                                        ];
                                        $icon = $icons[strtolower($materiel->categorie)] ?? 'fa-cube';
                                    @endphp
                                    <div class="h-9 w-9 flex-shrink-0 bg-slate-50 rounded-xl flex items-center justify-center border border-gray-100 group-hover:border-ci-green/20 transition-colors">
                                        <i class="fa-solid {{ $icon }} text-gray-300 group-hover:text-ci-green transition-colors text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">{{ $materiel->marque }}</p>
                                        <p class="text-[9px] font-bold text-gray-400 uppercase">{{ $materiel->modele }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-4">
                                <span class="text-xs font-bold text-slate-700">{{ $materiel->numero_serie }}</span>
                            </td>
                            <td class="px-8 py-4">
                                <span class="text-[9px] font-black px-2 py-1 bg-slate-50 text-slate-500 rounded border border-gray-100 uppercase tracking-wide">
                                    {{ $materiel->categorie }}
                                </span>
                            </td>
                            <td class="px-8 py-4">
                                @php
                                    $statusMap = [
                                        'disponible'    => 'bg-green-50 text-ci-green border-green-100',
                                        'affecté'       => 'bg-orange-50 text-ci-orange border-orange-100',
                                        'en_panne'      => 'bg-red-50 text-red-600 border-red-100',
                                        'en_reparation' => 'bg-blue-50 text-blue-600 border-blue-100',
                                        'rebut'         => 'bg-gray-100 text-gray-400 border-gray-200',
                                    ];
                                    $sc = $statusMap[$materiel->statut] ?? 'bg-gray-50 text-gray-500 border-gray-100';
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-md text-[8px] font-black uppercase tracking-widest border {{ $sc }}">
                                    <span class="h-1 w-1 rounded-full bg-current mr-1.5"></span>
                                    {{ $materiel->statut_label }}
                                </span>
                            </td>
                            <td class="px-8 py-4 text-right" onclick="event.stopPropagation()">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('materiels.show', $materiel) }}" class="p-2 text-gray-300 hover:text-ci-green transition-colors" title="Voir">
                                        <i class="fa-solid fa-qrcode text-sm"></i>
                                    </a>
                                    @if(auth()->user()->role === 'gestionnaire')
                                    <a href="{{ route('materiels.edit', $materiel) }}" class="p-2 text-gray-300 hover:text-ci-orange transition-colors" title="Modifier">
                                        <i class="fa-solid fa-pen-nib text-sm"></i>
                                    </a>
                                    <form action="{{ route('materiels.destroy', $materiel) }}" method="POST" class="inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                        @csrf @method('DELETE')
                                        <button class="p-2 text-gray-300 hover:text-red-500 transition-colors" title="Supprimer">
                                            <i class="fa-solid fa-trash-can text-sm"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <i class="fa-solid fa-box-open text-gray-200 text-3xl mb-3 block"></i>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Aucun matériel trouvé</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($materiels->hasPages())
        <div class="px-8 py-4 bg-slate-50/60 border-t border-gray-100">
            {{ $materiels->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
