<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-3xl text-gray-900 leading-tight tracking-tighter">
            Validations <span class="text-ci-orange">En Attente</span>
        </h2>
        <p class="text-xs font-bold text-gray-500 mt-2 uppercase tracking-widest">Demandes nécessitant votre approbation
            (Rôle: {{ Auth::user()->role }})</p>
    </x-slot>

    <!-- Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50">
                        <th
                            class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 uppercase">
                            Référence</th>
                        <th
                            class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 uppercase">
                            Demandeur</th>
                        <th
                            class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 uppercase">
                            Matériel</th>
                        <th
                            class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-right uppercase">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($demandes as $demande)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <span
                                    class="text-sm font-black text-gray-900 tracking-tighter">{{ $demande->reference }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs font-bold text-gray-700">{{ $demande->utilisateur->full_name }}</p>
                                <p class="text-[10px] text-gray-400">
                                    {{ $demande->utilisateur->service?->nom_service ?? 'Sans service' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs font-black text-gray-900 uppercase tracking-tighter">
                                    {{ $demande->categorie_demandee }}</p>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Qté:
                                    {{ $demande->quantite }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('demandes.show', $demande) }}"
                                    class="inline-flex items-center px-4 py-2 bg-slate-900 text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:scale-105 transition-all">
                                    Traiter <i class="fa-solid fa-arrow-right ml-2 text-ci-orange"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">
                                Aucune demande en attente de votre validation.
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