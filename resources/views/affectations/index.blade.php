<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-3xl text-gray-900 leading-tight tracking-tighter">
            Liste des <span class="text-ci-orange">Affectations</span>
        </h2>
        <p class="text-xs font-bold text-gray-500 mt-2 uppercase tracking-widest">Suivi du matériel distribué au personnel</p>
    </x-slot>

    <!-- Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 uppercase">Référence</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 uppercase">Bénéficiaire</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 uppercase">Matériel</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-center uppercase">Date</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-right uppercase">Bordereau</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($affectations as $affectation)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-sm font-black text-gray-900 tracking-tighter">{{ $affectation->reference_bordereau }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs font-bold text-gray-700">{{ $affectation->utilisateur->full_name }}</p>
                                <p class="text-[10px] text-gray-400 uppercase tracking-widest">{{ $affectation->utilisateur->role }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs font-black text-gray-900 uppercase tracking-tighter">{{ $affectation->materiel->marque }} {{ $affectation->materiel->modele }}</p>
                                <p class="text-[10px] font-bold text-gray-400">SN: {{ $affectation->materiel->numero_serie }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <p class="text-xs font-bold text-gray-600">{{ $affectation->date_affectation->format('d/m/Y') }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('affectations.download', $affectation) }}" class="inline-flex items-center px-4 py-2 bg-ci-green text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:translate-y-[-1px] transition-all shadow-lg shadow-green-900/10">
                                    <i class="fa-solid fa-file-pdf mr-2"></i> PDF
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">
                                Aucune affectation enregistrée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $affectations->links() }}
        </div>
    </div>
</x-app-layout>
