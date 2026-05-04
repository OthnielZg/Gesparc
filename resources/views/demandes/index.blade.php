<x-app-layout>
    <!-- En-tête cohérent avec le Dashboard -->
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight uppercase leading-none">
                Suivi des <span class="text-ci-green">Dossiers</span>
            </h1>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mt-2">Gestion du patrimoine informatique · DGI</p>
        </div>
        @unless(Auth::user()->hasRole(['admin', 'gestionnaire']))
        <a href="{{ route('demandes.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-ci-green text-white font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-opacity-90 transition-all">
            <i class="fa-solid fa-plus"></i> Nouveau Dossier
        </a>
        @endunless
    </div>

    <!-- Filtre style Radar -->
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden mb-8">
        <div class="px-8 py-4 bg-slate-50/60 border-b border-gray-100 flex items-center justify-between">
            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Recherche & Filtres</span>
        </div>
        <div class="px-8 py-6">
            <form action="{{ route('demandes.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="block w-full px-5 py-3 bg-slate-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold placeholder:text-gray-300"
                           placeholder="Référence, Matériel, Motif...">
                </div>
                <div class="w-full md:w-56">
                    <select name="statut" class="block w-full px-5 py-3 bg-slate-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold cursor-pointer">
                        <option value="">Tous les statuts</option>
                        @foreach(\App\Models\Demande::statutLabel() as $key => $label)
                            <option value="{{ $key }}" {{ request('statut') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-7 py-3 bg-slate-900 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-black transition-all">
                    Filtrer
                </button>
                @if(request()->hasAny(['search', 'statut']))
                <a href="{{ route('demandes.index') }}" class="text-gray-400 hover:text-red-500 font-black text-[10px] uppercase tracking-widest transition-colors whitespace-nowrap">
                    <i class="fa-solid fa-rotate-left mr-1"></i> Reset
                </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Tableau style Radar -->
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-8 py-4 bg-slate-50/60 border-b border-gray-100 flex items-center justify-between">
            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Liste des Dossiers</span>
            <span class="text-[9px] font-black text-gray-300 uppercase tracking-widest">{{ $demandes->total() }} résultats</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[9px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50">
                        <th class="px-8 py-4">Référence</th>
                        <th class="px-8 py-4">Demandeur</th>
                        <th class="px-8 py-4">Matériel</th>
                        <th class="px-8 py-4">Statut</th>
                        <th class="px-8 py-4 text-right">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($demandes as $demande)
                        <tr class="hover:bg-slate-50/50 transition-colors cursor-pointer"
                            onclick="window.location='{{ route('demandes.show', $demande) }}'">
                            <td class="px-8 py-4">
                                <span class="text-sm font-bold text-slate-900">{{ $demande->reference }}</span>
                            </td>
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 shrink-0 rounded-full bg-slate-100 flex items-center justify-center text-[10px] text-gray-500 font-black overflow-hidden ring-2 ring-white">
                                        @if($demande->utilisateur->photo_path)
                                            <img src="{{ asset('storage/' . $demande->utilisateur->photo_path) }}" alt="Avatar" class="h-full w-full object-cover">
                                        @else
                                            {{ substr($demande->utilisateur->prenom, 0, 1) }}{{ substr($demande->utilisateur->nom, 0, 1) }}
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-900">{{ $demande->utilisateur->full_name }}</p>
                                        <p class="text-[9px] font-bold text-gray-400 uppercase">{{ $demande->utilisateur->getRoleLabel() }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-4">
                                <p class="text-xs font-bold text-slate-800">{{ $demande->categorie_demandee }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[9px] font-bold text-gray-400 uppercase">Qté {{ $demande->quantite }}</span>
                                    @if($demande->priorite === 'urgente')
                                        <span class="text-[8px] font-black px-1.5 py-0.5 rounded bg-red-50 text-red-500 uppercase">Urgent</span>
                                    @elseif($demande->priorite === 'haute')
                                        <span class="text-[8px] font-black px-1.5 py-0.5 rounded bg-orange-50 text-ci-orange uppercase">Haute</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-4">
                                @php
                                    $colors = [
                                        'pre_demande'            => 'bg-blue-50 text-blue-600 border-blue-100',
                                        'en_attente_dr'          => 'bg-amber-50 text-amber-600 border-amber-100',
                                        'transmise_gestionnaire' => 'bg-orange-50 text-orange-600 border-orange-100',
                                        'proposition_sdgrte'     => 'bg-purple-50 text-purple-600 border-purple-100',
                                        'en_attente_dsi'         => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                        'validee'                => 'bg-green-50 text-ci-green border-green-100',
                                        'rejetee'                => 'bg-red-50 text-red-600 border-red-100',
                                        'annulee'                => 'bg-gray-50 text-gray-500 border-gray-100',
                                    ];
                                    $colorClass = $colors[$demande->statut] ?? 'bg-gray-50 text-gray-500 border-gray-100';
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-md text-[8px] font-black uppercase tracking-widest border {{ $colorClass }}">
                                    <span class="h-1 w-1 rounded-full bg-current mr-1.5"></span>
                                    {{ $demande->statut_label }}
                                </span>
                            </td>
                            <td class="px-8 py-4 text-right">
                                <p class="text-xs font-bold text-slate-800">{{ $demande->created_at->format('d/m/Y') }}</p>
                                <p class="text-[9px] font-bold text-gray-400">{{ $demande->created_at->format('H:i') }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fa-solid fa-folder-open text-gray-200 text-3xl mb-3"></i>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Aucun dossier trouvé</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($demandes->hasPages())
        <div class="px-8 py-4 bg-slate-50/60 border-t border-gray-100">
            {{ $demandes->links() }}
        </div>
        @endif
    </div>
</x-app-layout>