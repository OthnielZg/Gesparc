<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-black text-4xl text-gray-900 leading-tight tracking-tighter mb-1">
                Parc Informatique de la <span class="text-ci-orange">Direction</span>
            </h2>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                {{ $direction->nom_direction }} — Vue analytique DR
            </p>
        </div>
    </x-slot>

    {{-- Grand Total --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-ci-green-dark p-8 rounded-[2.5rem] shadow-2xl text-white relative overflow-hidden md:col-span-2">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
            <p class="text-[10px] font-black text-ci-orange uppercase tracking-[0.3em] mb-3">Patrimoine Total</p>
            <p class="text-6xl font-black tracking-tighter leading-none mb-2">{{ $grandTotal }}</p>
            <p class="text-xs font-bold text-white/50 uppercase tracking-widest">équipements actifs dans la direction</p>
        </div>
        <div class="bg-white p-8 rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.02)] border border-gray-100 flex flex-col justify-center">
            <div class="h-12 w-12 rounded-2xl bg-orange-50 flex items-center justify-center mb-4">
                <i class="fa-solid fa-sitemap text-ci-orange text-xl"></i>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Services sous tutelle</p>
            <p class="text-3xl font-black text-gray-900 tracking-tighter">{{ $servicesStats->count() }}</p>
        </div>
    </div>

    {{-- Services Cards --}}
    <div class="space-y-6">
        @forelse($servicesStats as $stat)
            @php
                $service = $stat['service'];
                $ratio = $stat['agentsCount'] > 0 ? round(($stat['totalMateriels'] / max($stat['agentsCount'], 1)) * 100) : 0;
                $ratio = min($ratio, 100);
            @endphp
            <div class="bg-white rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden">
                {{-- Service Header --}}
                <div class="px-8 py-6 flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-gray-50">
                    <div class="flex items-center gap-5">
                        <div class="h-14 w-14 rounded-2xl bg-gray-50 flex items-center justify-center border border-gray-100 text-gray-300">
                            <i class="fa-solid fa-building text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-gray-900 tracking-tighter">{{ $service->nom_service }}</h3>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                {{ $stat['bureauxCount'] }} bureau{{ $stat['bureauxCount'] > 1 ? 'x' : '' }}
                                &bull; {{ $stat['agentsCount'] }} agent{{ $stat['agentsCount'] > 1 ? 's' : '' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <div class="text-right">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Équipements</p>
                            <p class="text-3xl font-black text-gray-900 tracking-tighter">{{ $stat['totalMateriels'] }}</p>
                        </div>
                        <div class="h-16 w-16 rounded-2xl {{ $stat['totalMateriels'] > 0 ? 'bg-green-50' : 'bg-gray-50' }} flex flex-col items-center justify-center">
                            <i class="fa-solid fa-laptop {{ $stat['totalMateriels'] > 0 ? 'text-ci-green' : 'text-gray-300' }} text-xl"></i>
                        </div>
                    </div>
                </div>

                {{-- Progress Bar --}}
                @if($grandTotal > 0)
                <div class="px-8 py-4 bg-gray-50/50">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Part du patrimoine total</span>
                        <span class="text-[9px] font-black text-gray-600 uppercase">
                            {{ round(($stat['totalMateriels'] / $grandTotal) * 100) }}%
                        </span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-ci-green rounded-full transition-all duration-1000"
                             style="width: {{ $grandTotal > 0 ? round(($stat['totalMateriels'] / $grandTotal) * 100) : 0 }}%"></div>
                    </div>
                </div>
                @endif

                {{-- Bureaux breakdown --}}
                <div class="px-8 py-6">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Répartition par Bureau</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($service->bureaux as $bureau)
                            @php
                                $bureauUserIds = $bureau->utilisateurs->pluck('id');
                                $bureauMatCount = \App\Models\Affectation::whereIn('id_utilisateur', $bureauUserIds)
                                    ->whereNull('date_retour')->count();
                            @endphp
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100 hover:bg-white hover:shadow-sm transition-all">
                                <div>
                                    <p class="text-xs font-black text-gray-900">{{ $bureau->nom_bureau }}</p>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">
                                        {{ $bureau->utilisateurs->count() }} agents
                                    </p>
                                </div>
                                <div class="h-10 w-10 rounded-xl {{ $bureauMatCount > 0 ? 'bg-green-50 text-ci-green' : 'bg-gray-100 text-gray-300' }} flex items-center justify-center font-black text-sm">
                                    {{ $bureauMatCount }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-[2.5rem] border border-gray-100 p-20 text-center">
                <div class="h-20 w-20 bg-gray-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-gray-200">
                    <i class="fa-solid fa-building-circle-exclamation text-4xl"></i>
                </div>
                <p class="text-gray-900 font-black uppercase tracking-widest text-xs">Aucun service dans cette direction</p>
            </div>
        @endforelse
    </div>
</x-app-layout>
