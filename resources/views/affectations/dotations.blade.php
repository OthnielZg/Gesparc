<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="font-black text-4xl text-gray-900 leading-tight tracking-tighter mb-1">
                    Dotations <span class="text-ci-orange">& Finalisations</span>
                </h2>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest flex items-center gap-2">
                    <span class="h-1.5 w-1.5 rounded-full bg-ci-orange animate-pulse"></span>
                    Remise physique du matériel et signature du bordereau (Chef SEDMI)
                </p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- Alertes de session -->
        @if(session('success'))
            <div class="p-6 rounded-3xl bg-green-50 border border-green-100 flex items-center gap-4 text-green-700 animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="h-10 w-10 rounded-xl bg-white flex items-center justify-center shadow-sm text-green-500">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <p class="text-[10px] font-black uppercase tracking-widest">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="p-6 rounded-3xl bg-red-50 border border-red-100 flex items-center gap-4 text-red-700 animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="h-10 w-10 rounded-xl bg-white flex items-center justify-center shadow-sm text-red-500">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
                <p class="text-[10px] font-black uppercase tracking-widest">{{ session('error') }}</p>
            </div>
        @endif



        <!-- Liste des dotations -->
        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_60px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">Référence</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">Bénéficiaire</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">Matériel Validé</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 text-center">Priorité</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($demandes as $demande)
                            <tr class="hover:bg-gray-50/80 transition-all group">
                                <!-- Référence -->
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-xl bg-gray-50 flex items-center justify-center border border-gray-100 group-hover:bg-white group-hover:border-ci-orange/30 transition-all">
                                            <i class="fa-solid fa-box-open text-gray-300 group-hover:text-ci-orange transition-all"></i>
                                        </div>
                                        <div>
                                            <span class="text-sm font-black text-gray-900 tracking-tighter">{{ $demande->reference }}</span>
                                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ $demande->created_at->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Bénéficiaire -->
                                <td class="px-8 py-6">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full border-2 border-white shadow-sm ring-1 ring-gray-100 overflow-hidden mr-4 bg-gray-50 flex items-center justify-center text-[10px] font-black text-gray-400 uppercase">
                                            {{ substr($demande->utilisateur->prenom, 0, 1) }}{{ substr($demande->utilisateur->nom, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-black text-gray-900 uppercase tracking-tighter">{{ $demande->utilisateur->full_name }}</p>
                                            <p class="text-[9px] font-bold text-gray-400 uppercase truncate max-w-[150px]">
                                                {{ $demande->utilisateur->bureau?->service?->nom_service ?? 'DGI' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Matériel -->
                                <td class="px-8 py-6">
                                    <p class="text-xs font-black text-gray-900 uppercase tracking-tighter">{{ $demande->getMaterielRetenuLabel() }}</p>
                                    <p class="text-[9px] font-bold text-ci-orange uppercase tracking-widest mt-0.5 flex items-center gap-1">
                                        <i class="fa-solid fa-check-circle"></i>
                                        Arbitrage DSI validé
                                    </p>
                                </td>

                                <!-- Priorité -->
                                <td class="px-8 py-6 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest
                                        {{ $demande->priorite == 'urgente' ? 'bg-red-50 text-red-600' : ($demande->priorite == 'haute' ? 'bg-orange-50 text-orange-600' : 'bg-gray-100 text-gray-500') }}">
                                        {{ $demande->priorite }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="px-8 py-6 text-right">
                                    <a href="{{ route('affectations.finaliser', $demande) }}"
                                       class="inline-flex items-center gap-2 px-5 py-3 bg-black text-white text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-black/10 hover:bg-ci-orange hover:-translate-y-0.5 active:translate-y-0 transition-all">
                                        <i class="fa-solid fa-file-check text-xs"></i>
                                        Finaliser la Remise
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="h-20 w-20 bg-gray-50 rounded-[2rem] flex items-center justify-center mb-6">
                                            <i class="fa-solid fa-truck-ramp-box text-gray-200 text-3xl"></i>
                                        </div>
                                        <p class="text-gray-400 font-black uppercase tracking-[0.2em] text-xs">Tout est à jour !</p>
                                        <p class="text-gray-300 font-bold text-[10px] uppercase mt-1">Aucun matériel n'attend de remise physique pour le moment.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($demandes->hasPages())
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
                    {{ $demandes->links() }}
                </div>
            @endif
        </div>
    </div>

</x-app-layout>
