<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="font-black text-4xl text-gray-900 leading-tight tracking-tighter mb-1">
                    Dossiers <span class="text-ci-orange">à Traiter</span>
                </h2>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest flex items-center gap-2">
                    <span class="h-1.5 w-1.5 rounded-full bg-ci-orange animate-pulse"></span>
                    En attente de votre décision (Rôle: {{ Auth::user()->getRoleLabel() }})
                </p>
            </div>
        </div>
    </x-slot>

    <!-- Liste des demandes -->
    <div class="bg-white rounded-[2.5rem] shadow-[0_20px_60px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">Référence</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">Demandeur</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">Objet du Dossier</th>
                        @if(Auth::user()->isGestionnaire())
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 text-center">Priorité</th>
                        @endif
                        @if(Auth::user()->isSDGRTE())
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">Proposition SEDMI</th>
                        @endif
                        @if(Auth::user()->isDSI())
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">Propositions</th>
                        @endif
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($demandes as $demande)
                        <tr class="hover:bg-gray-50/80 transition-all">
                            <!-- Référence -->
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-xl bg-gray-50 flex items-center justify-center border border-gray-100">
                                        <i class="fa-solid fa-folder-open text-gray-300"></i>
                                    </div>
                                    <div>
                                        <span class="text-sm font-black text-gray-900 tracking-tighter">{{ $demande->reference }}</span>
                                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ $demande->created_at->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Demandeur -->
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full border-2 border-white shadow-sm ring-1 ring-gray-100 overflow-hidden mr-4">
                                        @if($demande->utilisateur->photo_path)
                                            <img src="{{ asset('storage/' . $demande->utilisateur->photo_path) }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full bg-ci-green-dark/5 flex items-center justify-center text-ci-green-dark font-black text-[10px]">
                                                {{ substr($demande->utilisateur->prenom, 0, 1) }}{{ substr($demande->utilisateur->nom, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-gray-900 uppercase tracking-tighter">{{ $demande->utilisateur->full_name }}</p>
                                        <p class="text-[9px] font-bold text-gray-400 uppercase truncate max-w-[150px]">
                                            {{ $demande->utilisateur->bureau?->service?->nom_service ?? ($demande->utilisateur->bureau?->nom_bureau ?? 'DGI') }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <!-- Objet -->
                            <td class="px-8 py-6">
                                <p class="text-xs font-black text-gray-900 uppercase tracking-tighter">{{ $demande->categorie_demandee }}</p>
                                <p class="text-[10px] font-bold text-gray-400 uppercase mt-0.5 tracking-widest">Quantité: {{ $demande->quantite }} unités</p>
                            </td>

                            <!-- Colonne Priorité (Gestionnaire) -->
                            @if(Auth::user()->isGestionnaire())
                                <td class="px-8 py-6 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest
                                        {{ $demande->priorite == 'urgente' ? 'bg-red-50 text-red-600' : ($demande->priorite == 'haute' ? 'bg-orange-50 text-orange-600' : 'bg-gray-100 text-gray-500') }}">
                                        {{ $demande->priorite }}
                                    </span>
                                </td>
                            @endif

                            <!-- Colonne Proposition SEDMI (SDGRTE) -->
                            @if(Auth::user()->isSDGRTE())
                                <td class="px-8 py-6">
                                    @if($demande->materiel_propose)
                                        <p class="text-xs font-black text-gray-900 uppercase tracking-tighter">{{ $demande->materiel_propose }}</p>
                                        <p class="text-[9px] font-bold text-purple-500 uppercase tracking-widest mt-0.5">Proposition SEDMI</p>
                                    @else
                                        <span class="text-gray-300 text-xs">—</span>
                                    @endif
                                </td>
                            @endif

                            <!-- Colonne Propositions (DSI) -->
                            @if(Auth::user()->isDSI())
                                <td class="px-8 py-6">
                                    <div class="space-y-1">
                                        <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest">A (SEDMI)</p>
                                        <p class="text-xs font-bold text-gray-800">{{ $demande->materiel_propose ?? '—' }}</p>
                                        @if($demande->hasContreProposition())
                                            <p class="text-[9px] font-black text-orange-500 uppercase tracking-widest mt-1">B (SDGRTE)</p>
                                            <p class="text-xs font-bold text-orange-700">{{ $demande->materiel_contre_propose }}</p>
                                        @endif
                                    </div>
                                </td>
                            @endif

                            <!-- Actions -->
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('demandes.show', $demande) }}"
                                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-ci-green-dark text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-black/10 hover:scale-105 active:scale-95 transition-all">
                                    <i class="fa-solid fa-file-signature text-xs"></i>
                                    Traiter
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="h-20 w-20 bg-gray-50 rounded-[2rem] flex items-center justify-center mb-6">
                                        <i class="fa-solid fa-check-double text-gray-200 text-3xl"></i>
                                    </div>
                                    <p class="text-gray-400 font-black uppercase tracking-[0.2em] text-xs">Excellente nouvelle !</p>
                                    <p class="text-gray-300 font-bold text-[10px] uppercase mt-1">Vous n'avez aucun dossier en attente de validation.</p>
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