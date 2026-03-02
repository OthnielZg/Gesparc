<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="font-black text-4xl text-gray-900 leading-tight tracking-tighter mb-1">
                    Mon <span class="text-ci-orange">Matériel</span>
                </h2>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Équipements informatiques qui vous sont affectés</p>
            </div>
        </div>
    </x-slot>

    @if($affectations->isEmpty())
        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_60px_rgba(0,0,0,0.03)] border border-gray-100 p-20 text-center">
            <div class="h-20 w-20 bg-gray-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-gray-200">
                <i class="fa-solid fa-laptop-slash text-4xl"></i>
            </div>
            <p class="text-gray-900 font-black uppercase tracking-widest text-xs">Aucun matériel affecté</p>
            <p class="text-gray-400 text-[10px] font-bold uppercase mt-2">Vous n'avez pas encore d'équipement attribué. Soumettez un signalement de besoin.</p>
            <a href="{{ route('demandes.create') }}" class="inline-flex items-center mt-8 px-8 py-4 bg-ci-green text-white font-black text-xs uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-green-900/10 hover:scale-105 transition-all active:scale-95">
                <i class="fa-solid fa-plus mr-3"></i> Faire une demande
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($affectations as $affectation)
                @php
                    $icons = [
                        'ordinateur' => ['icon' => 'fa-laptop-code', 'color' => 'text-blue-500', 'bg' => 'bg-blue-50'],
                        'imprimante' => ['icon' => 'fa-print', 'color' => 'text-orange-500', 'bg' => 'bg-orange-50'],
                        'scanner' => ['icon' => 'fa-barcode', 'color' => 'text-purple-500', 'bg' => 'bg-purple-50'],
                        'serveur' => ['icon' => 'fa-server', 'color' => 'text-gray-600', 'bg' => 'bg-gray-100'],
                        'onduleur' => ['icon' => 'fa-battery-full', 'color' => 'text-green-500', 'bg' => 'bg-green-50'],
                    ];
                    $cat = strtolower($affectation->materiel->categorie ?? 'autre');
                    $style = $icons[$cat] ?? ['icon' => 'fa-cube', 'color' => 'text-gray-500', 'bg' => 'bg-gray-50'];
                @endphp
                <div class="bg-white rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-100 p-8 relative overflow-hidden group hover:shadow-[0_20px_60px_rgba(0,0,0,0.06)] transition-all">
                    <div class="absolute top-0 right-0 w-32 h-32 {{ $style['bg'] }} opacity-30 rounded-full -translate-y-16 translate-x-16 pointer-events-none"></div>

                    <div class="flex items-start justify-between mb-6">
                        <div class="h-14 w-14 {{ $style['bg'] }} rounded-2xl flex items-center justify-center">
                            <i class="fa-solid {{ $style['icon'] }} {{ $style['color'] }} text-2xl"></i>
                        </div>
                        <span class="px-3 py-1 bg-green-50 text-ci-green border border-green-100 rounded-full text-[9px] font-black uppercase tracking-widest">
                            Actif
                        </span>
                    </div>

                    <h3 class="text-xl font-black text-gray-900 tracking-tighter mb-1">
                        {{ $affectation->materiel->marque }} {{ $affectation->materiel->modele }}
                    </h3>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">
                        {{ $affectation->materiel->categorie }}
                    </p>

                    <div class="space-y-3 border-t border-gray-50 pt-6">
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">N° de Série</span>
                            <span class="text-xs font-black text-gray-900 font-mono">{{ $affectation->materiel->numero_serie }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Date d'affectation</span>
                            <span class="text-xs font-black text-gray-900">{{ $affectation->date_affectation->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Réf. Bordereau</span>
                            <span class="text-xs font-black text-ci-orange">{{ $affectation->reference_bordereau }}</span>
                        </div>
                    </div>

                    <a href="{{ route('affectations.download', $affectation) }}"
                       class="mt-6 flex items-center justify-center gap-2 w-full py-4 bg-gray-50 text-gray-600 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-ci-green hover:text-white transition-all group-hover:shadow-sm">
                        <i class="fa-solid fa-download"></i> Télécharger le Bordereau
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</x-app-layout>
