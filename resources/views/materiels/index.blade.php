<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="font-black text-4xl text-gray-900 leading-tight tracking-tighter mb-1">
                    Inventaire du <span class="text-ci-orange">Matériel</span>
                </h2>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Base de données du patrimoine IT • DGI</p>
            </div>
            @if(auth()->user()->role !== 'admin')
            <a href="{{ route('materiels.create') }}" class="inline-flex items-center px-8 py-4 bg-ci-green text-white font-black text-xs uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-green-900/10 hover:scale-105 transition-all active:scale-95">
                <i class="fa-solid fa-plus mr-3"></i> Ajouter un Matériel
            </a>
            @endif
        </div>
    </x-slot>

    <!-- Quick Stats Over Inventory -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        @php
            $stats = [
                ['label' => 'Total Stock', 'val' => \App\Models\Materiel::count(), 'icon' => 'fa-cubes', 'color' => 'blue'],
                ['label' => 'Disponibles', 'val' => \App\Models\Materiel::where('statut', 'disponible')->count(), 'icon' => 'fa-box-open', 'color' => 'green'],
                ['label' => 'Affectés', 'val' => \App\Models\Materiel::where('statut', 'affecté')->count(), 'icon' => 'fa-user-tag', 'color' => 'orange'],
                ['label' => 'En Panne', 'val' => \App\Models\Materiel::where('statut', 'en_panne')->count(), 'icon' => 'fa-screwdriver-wrench', 'color' => 'red'],
            ];
        @endphp
        @foreach($stats as $s)
            <div class="bg-white p-6 rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.02)] border border-gray-100 flex items-center gap-5">
                <div class="h-12 w-12 rounded-2xl bg-{{ $s['color'] }}-50 text-{{ $s['color'] == 'green' ? 'ci-green' : ($s['color'] == 'orange' ? 'ci-orange' : $s['color'].'-500') }} flex items-center justify-center">
                    <i class="fa-solid {{ $s['icon'] }} text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">{{ $s['label'] }}</p>
                    <p class="text-xl font-black text-gray-900 tracking-tighter">{{ $s['val'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-2 rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.02)] border border-gray-100 mb-8 p-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gray-50/50 rounded-full -translate-y-16 translate-x-16 pointer-events-none"></div>
        <form action="{{ route('materiels.index') }}" method="GET" class="flex flex-col md:flex-row gap-6 items-end relative z-10">
            <div class="flex-1 w-full">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2 block">Rechercher une unité</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-gray-300">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="block w-full pl-12 pr-6 py-4 bg-gray-50/50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-2xl transition-all outline-none text-sm font-bold shadow-sm placeholder:text-gray-300"
                           placeholder="N° de série, Marque, Modèle...">
                </div>
            </div>
            <div class="w-full md:w-64">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2 block">État du Stock</label>
                <select name="statut" class="block w-full px-6 py-4 bg-gray-50/50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-2xl transition-all outline-none text-sm font-bold shadow-sm appearance-none cursor-pointer">
                    <option value="">Tous les matériels</option>
                    @foreach(\App\Models\Materiel::statutLabels() as $key => $label)
                        <option value="{{ $key }}" {{ request('statut') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="h-[56px] px-10 bg-ci-green-dark text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-xl shadow-black/10">
                Analyser
            </button>
            @if(request()->hasAny(['search', 'statut']))
                <a href="{{ route('materiels.index') }}" class="h-[56px] flex items-center px-4 text-gray-400 hover:text-red-500 font-black text-[10px] uppercase tracking-widest transition-colors mb-1">
                    <i class="fa-solid fa-rotate-left mr-2"></i> Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-[2.5rem] shadow-[0_20px_60px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">Désignation</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 uppercase">Identification</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">Catégorie</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 text-center">État Stock</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($materiels as $materiel)
                        <tr class="hover:bg-gray-50/80 transition-all cursor-pointer group" onclick="window.location='{{ route('materiels.show', $materiel) }}'">
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 flex-shrink-0 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-300 border border-gray-100 group-hover:bg-white transition-all group-hover:shadow-sm">
                                        @php
                                            $icons = [
                                                'ordinateur' => 'fa-laptop-code',
                                                'imprimante' => 'fa-print',
                                                'scanner' => 'fa-barcode',
                                                'serveur' => 'fa-server',
                                                'onduleur' => 'fa-battery-full',
                                            ];
                                            $icon = $icons[strtolower($materiel->categorie)] ?? 'fa-cube';
                                        @endphp
                                        <i class="fa-solid {{ $icon }} text-xl group-hover:text-ci-green transition-colors"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-black text-gray-900 uppercase tracking-tighter">{{ $materiel->marque }}</p>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $materiel->modele }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-hashtag text-[8px] text-gray-300"></i>
                                    <span class="text-xs font-black text-gray-700 tracking-tighter">{{ $materiel->numero_serie }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="inline-flex items-center px-3 py-1.5 bg-gray-50 rounded-xl border border-gray-100 group-hover:bg-white transition-all" title="{{ $materiel->categorie }}">
                                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest max-w-[100px] truncate block">
                                        {{ $materiel->categorie }}
                                    </span>
                                </span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                @php
                                    $colors = [
                                        'disponible' => 'bg-green-50 text-ci-green border-green-100',
                                        'affecté' => 'bg-orange-50 text-ci-orange border-orange-100',
                                        'en_panne' => 'bg-red-50 text-red-600 border-red-100',
                                        'en_reparation' => 'bg-blue-50 text-blue-600 border-blue-100',
                                        'rebut' => 'bg-gray-100 text-gray-400 border-gray-200',
                                    ];
                                    $colorClass = $colors[$materiel->statut] ?? 'bg-gray-50 text-gray-500 border-gray-100';
                                @endphp
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border leading-none {{ $colorClass }}">
                                    <span class="h-1 w-1 rounded-full bg-current mr-2"></span>
                                    {{ $materiel->statut_label }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end gap-3 opacity-20 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('materiels.show', $materiel) }}" class="p-2 text-gray-400 hover:text-ci-green transition-all hover:scale-125" title="Voir le Code QR / Étiquette" onclick="event.stopPropagation()">
                                        <i class="fa-solid fa-qrcode"></i>
                                    </a>
                                    <a href="{{ route('materiels.edit', $materiel) }}" class="p-2 text-gray-400 hover:text-ci-orange transition-all hover:scale-125" onclick="event.stopPropagation()">
                                        <i class="fa-solid fa-pen-nib"></i>
                                    </a>
                                    <form action="{{ route('materiels.destroy', $materiel) }}" method="POST" class="inline" onsubmit="return confirm('Confirmer la suppression définitive ?')" onclick="event.stopPropagation()">
                                        @csrf @method('DELETE')
                                        <button class="p-2 text-gray-400 hover:text-red-500 transition-all hover:scale-125">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-24 text-center">
                                <div class="max-w-xs mx-auto">
                                    <div class="h-20 w-20 bg-gray-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-gray-200">
                                        <i class="fa-solid fa-box-open text-4xl"></i>
                                    </div>
                                    <p class="text-gray-900 font-black uppercase tracking-widest text-xs">Stock épuisé ou vide</p>
                                    <p class="text-gray-400 text-[10px] font-bold uppercase mt-2 leading-relaxed">Aucun matériel ne correspond à vos critères de recherche actuels.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($materiels->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                {{ $materiels->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
