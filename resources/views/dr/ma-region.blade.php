<x-app-layout>
    <div class="mb-10">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight uppercase">Cité administrative : {{ $stats['nom_ville'] }}</h1>
                <div class="flex items-center space-x-2 mt-2">
                    <span class="h-1 w-12 bg-ci-green rounded-full"></span>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-widest">Ma Région • Hub de Supervision Territoriale</p>
                </div>
            </div>
            
            <!-- Regional Summary Cards -->
            <div class="flex items-center space-x-4">
                <div class="bg-white px-5 py-3 rounded-2xl shadow-sm border border-gray-100 text-center">
                    <span class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Directions</span>
                    <span class="text-xl font-black text-ci-green">{{ $stats['total_directions'] }}</span>
                </div>
                <div class="bg-white px-5 py-3 rounded-2xl shadow-sm border border-gray-100 text-center">
                    <span class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Services Total</span>
                    <span class="text-xl font-black text-ci-green">{{ $stats['total_services'] }}</span>
                </div>
                <div class="bg-white px-5 py-3 rounded-2xl shadow-sm border border-gray-100 text-center">
                    <span class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Demandes</span>
                    <span class="text-xl font-black text-ci-orange">{{ $stats['total_demandes'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 🌐 Matrice de Situation Régionale (HUB VERSION) -->
    @if($directions->isNotEmpty())
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-12">
        @foreach($directions as $dir)
        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_60px_rgb(0,0,0,0.02)] border border-gray-100 hover:border-ci-green/40 transition-all duration-500 overflow-hidden group">
            <div class="p-8">
                <!-- Header Component -->
                <div class="flex items-start justify-between mb-8">
                    <div class="flex items-center space-x-5">
                        <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-3xl flex items-center justify-center text-slate-800 shadow-sm transition-transform group-hover:bg-slate-900 group-hover:text-white group-hover:rotate-3 duration-500">
                            <i class="fa-solid fa-building-shield text-2xl"></i>
                        </div>
                        <div class="overflow-hidden">
                            <h4 class="text-2xl font-black text-gray-900 truncate leading-tight">{{ $dir->nom_direction }}</h4>
                            <div class="flex items-center space-x-3 mt-1">
                                <span class="px-2.5 py-1 bg-ci-green/10 text-ci-green rounded-xl text-[10px] font-black uppercase tracking-tight">Entité Officielle</span>
                                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">{{ $dir->services->count() }} Services Déployés</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- Column 1: Services -->
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <div class="flex items-center space-x-2">
                                <i class="fa-solid fa-sitemap text-ci-green text-xs"></i>
                                <span class="text-[11px] font-black text-gray-900 uppercase tracking-widest">Hiérarchie Interne</span>
                            </div>
                        </div>
                        <div class="space-y-4">
                            @forelse($dir->services->take(5) as $service)
                            <div class="flex items-center group/item cursor-default">
                                <div class="w-2 h-2 rounded-full border-2 border-ci-green mr-3 group-hover/item:bg-ci-green transition-colors"></div>
                                <span class="text-sm font-bold text-gray-600 truncate mr-2">{{ $service->nom_service }}</span>
                                <span class="h-px flex-1 bg-gray-50 mx-2"></span>
                                <span class="text-[10px] font-black text-gray-300 uppercase tracking-tighter">{{ $service->bureaux->count() }} Bur.</span>
                            </div>
                            @empty
                            <p class="text-sm font-bold text-gray-300 italic">Aucun service rattaché</p>
                            @endforelse
                            @if($dir->services->count() > 5)
                            <p class="text-[10px] font-black text-ci-green-dark uppercase pt-4 border-t border-gray-50 tracking-widest">+ {{ $dir->services->count() - 5 }} autres entités de service</p>
                            @endif
                        </div>
                    </div>

                    <!-- Column 2: Material Needs -->
                    <div class="border-l border-gray-100 pl-10">
                        <div class="flex items-center space-x-2 mb-5">
                            <i class="fa-solid fa-chart-line text-ci-orange text-xs"></i>
                            <span class="text-[11px] font-black text-gray-900 uppercase tracking-widest">Bilan des Besoins (Top 3)</span>
                        </div>
                        <div class="space-y-6">
                            @forelse($top = $dir->top_categories as $cat)
                            <div class="flex flex-col">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-black text-gray-700 tracking-tight">{{ $cat->categorie_demandee }}</span>
                                    <span class="text-sm font-black text-ci-orange">{{ $cat->total }} <span class="text-[9px] text-gray-400 font-bold uppercase ml-1">unités</span></span>
                                </div>
                                <div class="h-2 w-full bg-slate-50 rounded-full overflow-hidden border border-slate-100">
                                    <div class="h-full bg-ci-orange rounded-full shadow-sm" style="width: {{ ($cat->total / max($top->pluck('total')->max(), 1)) * 100 }}%"></div>
                                </div>
                            </div>
                            @empty
                            <div class="flex flex-col items-center justify-center py-4 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                                <i class="fa-solid fa-box-open text-gray-200 text-xl mb-1"></i>
                                <p class="text-[10px] font-bold text-gray-300 uppercase">Aucun besoin signalé</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bottom Navigation Bar -->
            <div class="bg-gray-50/50 px-10 py-6 border-t border-gray-100 flex items-center justify-between group-hover:bg-slate-50 transition-colors">
                <div class="flex items-center space-x-4">
                    <div class="flex -space-x-3">
                         @foreach($dir->services->take(4) as $s)
                            <div class="w-8 h-8 rounded-full border-2 border-white bg-white shadow-sm flex items-center justify-center text-[10px] font-black text-slate-700 uppercase" title="{{ $s->nom_service }}">
                                {{ substr($s->nom_service, 0, 1) }}
                            </div>
                         @endforeach
                    </div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest hidden sm:block">Configuration actuelle</span>
                </div>
                <div class="flex items-center space-x-6">
                    <div class="text-right border-r border-gray-200 pr-6">
                        <span class="block text-2xl font-black text-slate-900 leading-none">{{ $dir->total_demandes }}</span>
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Total Demandes</span>
                    </div>
                    <a href="{{ route('demandes.index') }}?direction={{ $dir->id }}" class="group/btn flex items-center space-x-3 bg-white px-5 py-3 rounded-2xl border border-gray-100 shadow-sm text-[11px] font-black text-ci-green hover:bg-ci-green hover:text-white transition-all uppercase tracking-widest">
                        <span>Explorer le bilan</span>
                        <i class="fa-solid fa-arrow-right-long transition-transform group-hover/btn:translate-x-1"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-[3rem] p-20 flex flex-col items-center justify-center border border-dashed border-gray-200 text-center">
        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-6">
            <i class="fa-solid fa-map-location-dot text-slate-300 text-3xl"></i>
        </div>
        <h3 class="text-2xl font-black text-gray-900 mb-2 uppercase">Aucune direction trouvée</h3>
        <p class="text-gray-400 font-bold max-w-sm">Le périmètre de votre ville semble ne contenir aucune direction rattachée pour le moment.</p>
    </div>
    @endif
</x-app-layout>
