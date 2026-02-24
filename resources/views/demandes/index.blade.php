<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-black text-3xl text-gray-900 leading-tight tracking-tighter">
                Suivi des <span class="text-ci-orange">Demandes</span>
            </h2>
            <a href="{{ route('demandes.create') }}" class="inline-flex items-center px-6 py-3 bg-ci-green text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg shadow-green-900/10 hover:scale-105 transition-all">
                <i class="fa-solid fa-plus mr-2"></i> Nouvelle Demande
            </a>
        </div>
    </x-slot>

    <!-- Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 uppercase">Référence</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 uppercase">Demandeur</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 uppercase">Matériel</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-center uppercase">Statut</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-right uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($demandes as $demande)
                        <tr class="hover:bg-gray-50/50 transition-colors cursor-pointer" onclick="window.location='{{ route('demandes.show', $demande) }}'">
                            <td class="px-6 py-4">
                                <span class="text-sm font-black text-gray-900 tracking-tighter">{{ $demande->reference }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs mr-3">
                                        {{ substr($demande->utilisateur->prenom, 0, 1) }}{{ substr($demande->utilisateur->nom, 0, 1) }}
                                    </div>
                                    <p class="text-xs font-bold text-gray-700">{{ $demande->utilisateur->full_name }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs font-black text-gray-900 uppercase tracking-tighter">{{ $demande->categorie_demandee }}</p>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Qté: {{ $demande->quantite }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest 
                                    bg-{{ $demande->statut_color }}-50 text-{{ $demande->statut_color == 'green' ? 'ci-green' : ($demande->statut_color == 'orange' ? 'ci-orange' : $demande->statut_color) }}-600">
                                    {{ $demande->statut_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <p class="text-xs font-bold text-gray-600">{{ $demande->created_at->format('d/m/Y') }}</p>
                                <p class="text-[10px] font-medium text-gray-400">{{ $demande->created_at->format('H:i') }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <p class="text-gray-500 font-bold">Aucune demande trouvée.</p>
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
