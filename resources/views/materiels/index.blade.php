<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-black text-3xl text-gray-900 leading-tight tracking-tighter">
                Gestion des <span class="text-ci-orange">Matériels</span>
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('materiels.create') }}" class="inline-flex items-center px-6 py-3 bg-ci-green text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-lg shadow-green-900/10 hover:scale-105 transition-all">
                    <i class="fa-solid fa-plus mr-2"></i> Ajouter un matériel
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Filters & Search -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 mb-8">
        <form action="{{ route('materiels.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">Recherche</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="block w-full pl-10 pr-4 py-3 bg-gray-50 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm"
                           placeholder="N° Série, Marque, Modèle...">
                </div>
            </div>
            <div class="w-full md:w-48">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">Statut</label>
                <select name="statut" class="block w-full px-4 py-3 bg-gray-50 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm">
                    <option value="">Tous les statuts</option>
                    @foreach(\App\Models\Materiel::statutLabels() as $key => $label)
                        <option value="{{ $key }}" {{ request('statut') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-6 py-3 bg-slate-900 text-white rounded-xl font-bold text-sm hover:bg-slate-800 transition-colors">
                Filtrer
            </button>
            @if(request()->hasAny(['search', 'statut']))
                <a href="{{ route('materiels.index') }}" class="px-4 py-3 text-gray-400 hover:text-gray-900 font-bold text-sm">Réinitialiser</a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Matériel</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 uppercase">N° Série</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Catégorie</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-center">Statut</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($materiels as $materiel)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 bg-slate-100 rounded-xl flex items-center justify-center text-slate-500 mr-3">
                                        <i class="fa-solid fa-laptop-code text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-gray-900 uppercase tracking-tighter">{{ $materiel->marque }}</p>
                                        <p class="text-xs font-medium text-gray-500">{{ $materiel->modele }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-600">{{ $materiel->numero_serie }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                    {{ $materiel->categorie }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest 
                                    bg-{{ $materiel->statut_color }}-50 text-{{ $materiel->statut_color == 'green' ? 'ci-green' : ($materiel->statut_color == 'orange' ? 'ci-orange' : $materiel->statut_color) }}-600">
                                    <span class="h-1.5 w-1.5 rounded-full bg-current mr-2"></span>
                                    {{ $materiel->statut_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('materiels.show', $materiel) }}" class="p-2 text-gray-400 hover:text-ci-green transition-colors"><i class="fa-solid fa-eye"></i></a>
                                    <a href="{{ route('materiels.edit', $materiel) }}" class="p-2 text-gray-400 hover:text-blue-600 transition-colors"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <form action="{{ route('materiels.destroy', $materiel) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce matériel ?')">
                                        @csrf @method('DELETE')
                                        <button class="p-2 text-gray-400 hover:text-red-600 transition-colors"><i class="fa-solid fa-trash-can"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="max-w-xs mx-auto">
                                    <div class="h-16 w-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                                        <i class="fa-solid fa-inbox text-3xl"></i>
                                    </div>
                                    <p class="text-gray-900 font-bold">Aucun matériel trouvé</p>
                                    <p class="text-gray-500 text-sm mt-1">Ajustez vos filtres ou ajoutez-en un nouveau.</p>
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
