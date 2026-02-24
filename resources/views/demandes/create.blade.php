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

    <div class="max-w-3xl">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8">
                <form action="{{ route('demandes.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Type de Matériel -->
                        <div class="space-y-1">
                            <label for="categorie_demandee" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Type de Matériel souhaité</label>
                            <select name="categorie_demandee" id="categorie_demandee" required
                                class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm">
                                <option value="">Choisir un type...</option>
                                @foreach(\App\Models\Materiel::categories() as $category)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Quantité -->
                        <div class="space-y-1">
                            <label for="quantite" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Quantité</label>
                            <input type="number" name="quantite" id="quantite" value="1" min="1" required
                                class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm">
                        </div>

                        <!-- Priorité -->
                        <div class="space-y-1 md:col-span-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block mb-3">Degré d'Urgence</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @foreach(['basse' => 'Basse', 'normale' => 'Normale', 'haute' => 'Haute', 'urgente' => 'Urgente'] as $val => $label)
                                    <label class="relative flex items-center justify-center p-3 border-2 border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50 transition-all has-[:checked]:border-ci-green has-[:checked]:bg-green-50">
                                        <input type="radio" name="priorite" value="{{ $val }}" required class="sr-only" {{ $val == 'normale' ? 'checked' : '' }}>
                                        <span class="text-xs font-black uppercase tracking-widest text-gray-600">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Motif -->
                        <div class="space-y-1 md:col-span-2">
                            <label for="motif_demande" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Motif de la demande</label>
                            <textarea name="motif_demande" id="motif_demande" rows="4" required
                                class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm"
                                placeholder="Expliquez pourquoi vous avez besoin de ce matériel (ex: Renouvellement, nouvel arrivant...)"></textarea>
                        </div>
                    </div>

                    <div class="pt-6 flex justify-end gap-4">
                        <a href="{{ route('demandes.index') }}" class="px-6 py-3 border border-gray-200 text-gray-500 rounded-xl font-bold text-sm hover:bg-gray-50 transition-colors">Annuler</a>
                        <button type="submit" class="px-8 py-3 bg-ci-green text-white rounded-xl font-black text-sm hover:scale-105 shadow-xl shadow-green-900/10 transition-all">Soumettre la Pré-demande</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
