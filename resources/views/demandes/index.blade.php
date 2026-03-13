<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="font-black text-4xl text-gray-900 leading-tight tracking-tighter mb-1">
                    Suivi des <span class="text-ci-orange">Dossiers</span>
                </h2>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Gestion du patrimoine informatique
                    • DGI</p>
            </div>
            @unless(Auth::user()->hasRole(['admin', 'gestionnaire']))
            <a href="{{ route('demandes.create') }}" class="inline-flex items-center px-8 py-4 bg-ci-green text-white font-black text-xs uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-green-900/10 hover:scale-105 transition-all active:scale-95">
                <i class="fa-solid fa-plus mr-3"></i> Nouvelle Demande
            </a>
            @endunless
        </div>
    </x-slot>
    
    <!-- Filtres et Recherche -->
    <div class="bg-white p-2 rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.02)] border border-gray-100 mb-8 p-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gray-50/50 rounded-full -translate-y-16 translate-x-16 pointer-events-none"></div>
        <form action="{{ route('demandes.index') }}" method="GET" class="flex flex-col md:flex-row gap-6 items-end relative z-10">
            <div class="flex-1 w-full">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2 block">Rechercher un dossier</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-gray-300">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="block w-full pl-12 pr-6 py-4 bg-gray-50/50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-2xl transition-all outline-none text-sm font-bold shadow-sm placeholder:text-gray-300"
                           placeholder="Référence, Matériel, Motif...">
                </div>
            </div>
            <div class="w-full md:w-64">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2 block">Filtrer par statut</label>
                <select name="statut" class="block w-full px-6 py-4 bg-gray-50/50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-2xl transition-all outline-none text-sm font-bold shadow-sm appearance-none cursor-pointer">
                    <option value="">Tous les dossiers</option>
                    @foreach(\App\Models\Demande::statutLabel() as $key => $label)
                        <option value="{{ $key }}" {{ request('statut') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="h-[56px] px-10 bg-ci-green-dark text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-xl shadow-black/10">
                Filtrer
            </button>
            @if(request()->hasAny(['search', 'statut']))
                <a href="{{ route('demandes.index') }}" class="h-[56px] flex items-center px-4 text-gray-400 hover:text-red-500 font-black text-[10px] uppercase tracking-widest transition-colors mb-1">
                    <i class="fa-solid fa-rotate-left mr-2"></i> Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Tableau -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th
                            class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 uppercase">
                            Référence</th>
                        <th
                            class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 uppercase">
                            Demandeur</th>
                        <th
                            class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 uppercase">
                            Matériel</th>
                        <th
                            class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 text-center uppercase">
                            Statut</th>
                        <th
                            class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 text-right uppercase">
                            Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($demandes as $demande)
                        <tr class="hover:bg-gray-50/80 transition-all cursor-pointer group"
                            onclick="window.location='{{ route('demandes.show', $demande) }}'">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-10 w-10 rounded-xl bg-gray-50 flex items-center justify-center border border-gray-100 group-hover:bg-white transition-colors">
                                        <i class="fa-solid fa-hashtag text-gray-300"></i>
                                    </div>
                                    <span
                                        class="text-sm font-black text-gray-900 tracking-tighter">{{ $demande->reference }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div
                                        class="h-10 w-10 rounded-full border-2 border-white shadow-sm ring-1 ring-gray-100 overflow-hidden mr-4">
                                        @if($demande->utilisateur->photo_url)
                                            <img src="{{ asset('storage/' . $demande->utilisateur->photo_url) }}"
                                                class="h-full w-full object-cover">
                                        @else
                                            <div
                                                class="h-full w-full bg-ci-green-dark/5 flex items-center justify-center text-ci-green-dark font-black text-xs">
                                                {{ substr($demande->utilisateur->prenom, 0, 1) }}{{ substr($demande->utilisateur->nom, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-gray-900">{{ $demande->utilisateur->full_name }}
                                        </p>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase">{{ $demande->utilisateur->getRoleLabel() }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-xs font-black text-gray-900 uppercase tracking-tight">
                                    {{ $demande->categorie_demandee }}
                                </p>
                                <div class="flex items-center gap-2 mt-1.5">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        Qté {{ $demande->quantite }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full
                                        {{ $demande->priorite == 'urgente' ? 'bg-red-50 text-red-500' : ($demande->priorite == 'haute' ? 'bg-orange-50 text-orange-500' : 'bg-gray-100 text-gray-400') }}">
                                        <span class="h-1.5 w-1.5 rounded-full bg-current flex-shrink-0"></span>
                                        {{ $demande->priorite }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                @php
                                    $colors = [
                                        'pre_demande' => 'bg-blue-50 text-blue-600 border-blue-100',
                                        'en_attente_dr' => 'bg-amber-50 text-amber-600 border-amber-100',
                                        'transmise_gestionnaire' => 'bg-orange-50 text-orange-600 border-orange-100',
                                        'proposition_sdgrte' => 'bg-purple-50 text-purple-600 border-purple-100',
                                        'en_attente_dsi' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                        'validee' => 'bg-green-50 text-ci-green border-green-100',
                                        'rejetee' => 'bg-red-50 text-red-600 border-red-100',
                                        'annulee' => 'bg-gray-50 text-gray-600 border-gray-100',
                                    ];
                                    $colorClass = $colors[$demande->statut] ?? 'bg-gray-50 text-gray-600 border-gray-100';
                                @endphp
                                <span
                                    class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $colorClass }}">
                                    <span class="h-1 w-1 rounded-full bg-current mr-2"></span>
                                    {{ $demande->statut_label }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <p class="text-xs font-black text-gray-900 tracking-tighter">
                                    {{ $demande->created_at->format('d/m/Y') }}</p>
                                <p class="text-[10px] font-bold text-gray-400 uppercase">
                                    {{ $demande->created_at->format('H:i') }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="h-16 w-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                        <i class="fa-solid fa-folder-open text-gray-200 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Aucune demande
                                        trouvée.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $demandes->links() }}
        </div>
    </div>
</x-app-layout>