<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-black text-4xl text-gray-900 leading-tight tracking-tighter mb-1">
                Parc de <span class="text-ci-orange">Mon Service</span>
            </h2>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                {{ $service?->nom_service ?? 'Service non renseigné' }} — Vue superviseur
            </p>
        </div>
    </x-slot>

    @if(!$service)
        <div class="bg-white rounded-[2.5rem] border border-gray-100 p-20 text-center">
            <div class="h-20 w-20 bg-gray-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-gray-200">
                <i class="fa-solid fa-building-circle-exclamation text-4xl"></i>
            </div>
            <p class="text-gray-900 font-black uppercase tracking-widest text-xs">Aucun service associé</p>
            <p class="text-gray-400 text-[10px] font-bold uppercase mt-2">Votre compte n'est pas encore rattaché à un bureau ou un service.</p>
        </div>
    @else
        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-7 rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.02)] border border-gray-100 flex items-center gap-5">
                <div class="h-12 w-12 rounded-2xl bg-green-50 text-ci-green flex items-center justify-center">
                    <i class="fa-solid fa-laptop text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Total Équipements</p>
                    <p class="text-2xl font-black text-gray-900 tracking-tighter">{{ $affectations->count() }}</p>
                </div>
            </div>
            <div class="bg-white p-7 rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.02)] border border-gray-100 flex items-center gap-5">
                <div class="h-12 w-12 rounded-2xl bg-orange-50 text-ci-orange flex items-center justify-center">
                    <i class="fa-solid fa-users text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Agents du Service</p>
                    <p class="text-2xl font-black text-gray-900 tracking-tighter">{{ $membres->count() }}</p>
                </div>
            </div>
            <div class="bg-white p-7 rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.02)] border border-gray-100 flex items-center gap-5">
                <div class="h-12 w-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center">
                    <i class="fa-solid fa-user-tag text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Agents Équipés</p>
                    <p class="text-2xl font-black text-gray-900 tracking-tighter">
                        {{ $membres->filter(fn($m) => $m->affectations->whereNull('date_retour')->count() > 0)->count() }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Members Table --}}
        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_60px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden mb-8">
            <div class="px-8 py-6 border-b border-gray-50 flex items-center gap-4">
                <div class="h-10 w-10 rounded-2xl bg-ci-green-dark/5 flex items-center justify-center">
                    <i class="fa-solid fa-users text-ci-green text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-black text-gray-900 uppercase tracking-tighter">Membres du Service</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Répartition du matériel par agent</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">Agent</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">Bureau</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 text-center">Nb Équipements</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">Équipements</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($membres as $membre)
                            @php
                                $myAffectations = $membre->affectations->whereNull('date_retour');
                            @endphp
                            <tr class="hover:bg-gray-50/80 transition-all group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full border-2 border-white shadow-sm ring-1 ring-gray-100 overflow-hidden mr-4 flex-shrink-0">
                                            @if($membre->photo_url)
                                                <img src="{{ asset('storage/' . $membre->photo_url) }}" class="h-full w-full object-cover">
                                            @else
                                                <div class="h-full w-full bg-ci-green-dark/5 flex items-center justify-center text-ci-green-dark font-black text-xs">
                                                    {{ substr($membre->prenom, 0, 1) }}{{ substr($membre->nom, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-xs font-black text-gray-900">{{ $membre->full_name }}</p>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $membre->role_label }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-xs font-bold text-gray-600">{{ $membre->bureau?->nom_bureau ?? '—' }}</span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-xl font-black text-sm
                                        {{ $myAffectations->count() > 0 ? 'bg-green-50 text-ci-green' : 'bg-gray-50 text-gray-400' }}">
                                        {{ $myAffectations->count() }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    @if($myAffectations->isEmpty())
                                        <span class="text-[10px] font-bold text-gray-300 uppercase">Aucun équipement</span>
                                    @else
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($myAffectations as $aff)
                                                <span class="px-3 py-1 bg-gray-50 text-gray-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-gray-100">
                                                    {{ $aff->materiel->marque ?? '?' }} {{ $aff->materiel->modele ?? '' }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-16 text-center text-gray-400 font-black text-xs uppercase tracking-widest">
                                    Aucun membre trouvé dans ce service.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Full Affectation List --}}
        @if($affectations->isNotEmpty())
        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_60px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 flex items-center gap-4">
                <div class="h-10 w-10 rounded-2xl bg-orange-50 flex items-center justify-center">
                    <i class="fa-solid fa-boxes-stacked text-ci-orange text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-black text-gray-900 uppercase tracking-tighter">Inventaire Complet du Service</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tous les équipements actifs</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">Équipement</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">N° Série</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">Affecté à</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">Bureau</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($affectations as $aff)
                            <tr class="hover:bg-gray-50/80 transition-all">
                                <td class="px-8 py-6">
                                    <p class="text-xs font-black text-gray-900 uppercase tracking-tighter">{{ $aff->materiel->marque }} {{ $aff->materiel->modele }}</p>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase mt-0.5">{{ $aff->materiel->categorie }}</p>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-xs font-black text-gray-700 font-mono">{{ $aff->materiel->numero_serie }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="text-xs font-black text-gray-900">{{ $aff->utilisateur->full_name }}</p>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="px-3 py-1 bg-gray-50 text-gray-500 rounded-lg text-[9px] font-black uppercase tracking-widest border border-gray-100">
                                        {{ $aff->utilisateur->bureau?->nom_bureau ?? '—' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    @endif
</x-app-layout>
