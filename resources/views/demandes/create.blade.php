<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('demandes.index') }}" class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-ci-green transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="font-black text-3xl text-gray-900 leading-tight tracking-tighter">
                Nouvelle <span class="text-ci-orange">Demande</span>
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <!-- Main Form Area -->
        <div class="w-full">
            <div class="bg-white rounded-[2.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden">
                <div class="p-8 sm:p-10">
                    <form action="{{ route('demandes.store') }}" method="POST" class="space-y-8">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Type de Matériel -->
                            <div class="space-y-2">
                                <label for="categorie_demandee" class="text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Type de Matériel souhaité</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fa-solid fa-laptop-code text-ci-green opacity-40 group-focus-within:opacity-100 transition-opacity"></i>
                                    </div>
                                    <select name="categorie_demandee" id="categorie_demandee" required
                                        class="block w-full pl-12 pr-4 py-4 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-2xl transition-all outline-none text-sm font-bold text-gray-700 shadow-sm appearance-none">
                                        <option value="">Choisir un type...</option>
                                        @foreach(\App\Models\Materiel::categories() as $category)
                                            <option value="{{ $category }}">{{ $category }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <i class="fa-solid fa-chevron-down text-xs text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Quantité -->
                            <div class="space-y-2">
                                <label for="quantite" class="text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Quantité de produits</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fa-solid fa-boxes-stacked text-ci-green opacity-40 group-focus-within:opacity-100 transition-opacity"></i>
                                    </div>
                                    <input type="number" name="quantite" id="quantite" value="1" min="1" required
                                        class="block w-full pl-12 pr-4 py-4 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-2xl transition-all outline-none text-sm font-bold text-gray-700 shadow-sm">
                                </div>
                            </div>

                            <!-- Priorité -->
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Urgence</label>
                                <div class="flex gap-2 flex-wrap">
                                    @php
                                        $priorities = [
                                            'basse'   => ['label' => 'Basse',   'active' => 'has-[:checked]:bg-blue-50 has-[:checked]:border-blue-300 has-[:checked]:text-blue-700'],
                                            'normale' => ['label' => 'Normale', 'active' => 'has-[:checked]:bg-green-50 has-[:checked]:border-ci-green has-[:checked]:text-ci-green'],
                                            'haute'   => ['label' => 'Haute',   'active' => 'has-[:checked]:bg-orange-50 has-[:checked]:border-ci-orange has-[:checked]:text-ci-orange'],
                                            'urgente' => ['label' => 'Urgente', 'active' => 'has-[:checked]:bg-red-50 has-[:checked]:border-red-500 has-[:checked]:text-red-600'],
                                        ];
                                    @endphp
                                    @foreach($priorities as $val => $data)
                                        <label class="flex items-center h-[54px] px-5 border-2 border-gray-100 rounded-2xl cursor-pointer bg-gray-50 hover:bg-white transition-all {{ $data['active'] }} group">
                                            <input type="radio" name="priorite" value="{{ $val }}" required class="sr-only" {{ $val == 'normale' ? 'checked' : '' }}>
                                            <span class="text-xs font-black uppercase tracking-widest text-gray-400 transition-colors">{{ $data['label'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Motif -->
                            <div class="space-y-2 md:col-span-3">
                                <label for="motif_demande" class="text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Motif détaillé de la demande</label>
                                <textarea name="motif_demande" id="motif_demande" rows="5" required
                                    class="block w-full px-6 py-4 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-[1.5rem] transition-all outline-none text-sm font-bold text-gray-700 shadow-sm"
                                    placeholder="Détaillez votre besoin ici... (ex: Renouvellement matériel, recrutement, panne irréparable...)"></textarea>
                            </div>
                        </div>

                        <div class="pt-8 flex flex-col sm:flex-row justify-end gap-4 border-t border-gray-50">
                            <a href="{{ route('demandes.index') }}" class="px-8 py-4 text-gray-400 font-bold text-sm hover:text-gray-600 transition-colors text-center uppercase tracking-widest">Annuler</a>
                            <button type="submit" class="px-10 py-4 bg-ci-green text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:scale-105 shadow-xl shadow-green-900/10 transition-all active:scale-95">
                                Soumettre le Signalement de besoin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
