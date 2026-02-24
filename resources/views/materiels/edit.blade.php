<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('materiels.index') }}" class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-ci-green transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="font-black text-3xl text-gray-900 leading-tight tracking-tighter">
                Modifier <span class="text-ci-orange">Matériel</span>
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8">
                <form action="{{ route('materiels.update', $materiel) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- N° Série -->
                        <div class="space-y-1">
                            <label for="numero_serie" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Numéro de Série</label>
                            <input type="text" name="numero_serie" id="numero_serie" value="{{ old('numero_serie', $materiel->numero_serie) }}" required
                                class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm"
                                placeholder="ex: SN123456789">
                            @error('numero_serie') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <!-- Catégorie -->
                        <div class="space-y-1">
                            <label for="categorie" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Catégorie</label>
                            <select name="categorie" id="categorie" required
                                class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm">
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ old('categorie', $materiel->categorie) == $category ? 'selected' : '' }}>{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Marque -->
                        <div class="space-y-1">
                            <label for="marque" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Marque</label>
                            <input type="text" name="marque" id="marque" value="{{ old('marque', $materiel->marque) }}" required
                                class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm">
                        </div>

                        <!-- Modèle -->
                        <div class="space-y-1">
                            <label for="modele" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Modèle</label>
                            <input type="text" name="modele" id="modele" value="{{ old('modele', $materiel->modele) }}" required
                                class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm">
                        </div>

                        <!-- Statut -->
                        <div class="space-y-1">
                            <label for="statut" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Statut</label>
                            <select name="statut" id="statut" required
                                class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm">
                                @foreach(\App\Models\Materiel::statutLabels() as $key => $label)
                                    <option value="{{ $key }}" {{ old('statut', $materiel->statut) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Prix -->
                        <div class="space-y-1">
                            <label for="prix_acquisition" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Prix d'acquisition (FCFA)</label>
                            <input type="number" name="prix_acquisition" id="prix_acquisition" value="{{ old('prix_acquisition', $materiel->prix_acquisition) }}"
                                class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm">
                        </div>

                        <!-- Fournisseur -->
                        <div class="space-y-1 md:col-span-2">
                            <label for="id_fournisseur" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Fournisseur</label>
                            <select name="id_fournisseur" id="id_fournisseur"
                                class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm">
                                <option value="">Inconnu / Aucun</option>
                                @foreach($fournisseurs as $fournisseur)
                                    <option value="{{ $fournisseur->id }}" {{ old('id_fournisseur', $materiel->id_fournisseur) == $fournisseur->id ? 'selected' : '' }}>{{ $fournisseur->nom_fournisseur }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Description -->
                        <div class="space-y-1 md:col-span-2">
                            <label for="description" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 block">Description / Notes</label>
                            <textarea name="description" id="description" rows="3"
                                class="block w-full px-4 py-3 bg-gray-50 border-2 border-transparent focus:border-ci-green focus:bg-white rounded-xl transition-all outline-none text-sm font-bold shadow-sm">{{ old('description', $materiel->description) }}</textarea>
                        </div>
                    </div>

                    <div class="pt-6 flex justify-end gap-4">
                        <a href="{{ route('materiels.index') }}" class="px-6 py-3 border border-gray-200 text-gray-500 rounded-xl font-bold text-sm hover:bg-gray-50 transition-colors">Annuler</a>
                        <button type="submit" class="px-8 py-3 bg-slate-900 text-white rounded-xl font-black text-sm hover:bg-slate-800 shadow-xl shadow-slate-900/10 transition-all">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
